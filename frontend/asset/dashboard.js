document.addEventListener("DOMContentLoaded", function(){
    mostraEventi();

    const eventForm = document.getElementById("eventForm");

    document.getElementById("applyFilters").addEventListener("click", function(){
        mostraEventi();
    });

    const data = document.querySelector("[name='data']");
    const stato = document.querySelector("[name='stato']");
    const titolo = document.querySelector("[name='titolo']");
    const ora = document.querySelector("[name='ora']");

    stato.addEventListener("change", function(){
        checkEvento(data, ora, stato);
    });

    data.addEventListener("change", function(){
        checkEvento(data, ora, stato);
    });

    ora.addEventListener("change", function(){
        checkEvento(data, ora, stato);
    });

    eventForm.addEventListener("submit", function(e){
        e.preventDefault();

        const titoloVal = titolo.value.trim();
        const dataVal = data.value;
        const statoVal = stato.value;
        const oraVal = ora.value;

        if (!titoloVal || !dataVal || !statoVal || !oraVal) {
            showMessage("Tutti i campi devono essere compilati", "error");
            return;
        }

        const formData = new FormData(this);

        fetch("../backend/functions/create_event.php", {
            method:"POST",
            body: formData
        })
        .then(res => res.json())
        .then(response => {

            if(response.success){

                this.reset();
                mostraEventi();

                showMessage(response.message, "success");

            } else {

                showMessage(response.message, "error");
            }

        });
    });
});

// controllo dai input del form prima di registrae un evento
function checkEvento(data, ora, stato){

    const adesso = new Date();

    const dataEvento = new Date(
        `${data.value}T${ora.value}`
    );


    // evento concluso ma ancora nel futuro
    if (parseInt(stato.value) === 2 && dataEvento > adesso) {

        showMessage("Un evento futuro non può essere concluso", "error");

        return false;
    }


    // evento in programma ma già passato
    if (parseInt(stato.value) === 1 && dataEvento < adesso) {

        showMessage("Un evento passato non può essere messo in programma", "error");

        return false;
    }


    return true;
}

function mostraEventi(){
    const sort = document.getElementById("sort")?.value;
    const filter = document.getElementById("filter")?.value;
    const from = document.getElementById("from")?.value;
    const to = document.getElementById("to")?.value;
    
    let url_backend = "../backend/functions/fetch_list_event.php?";

    if(sort) url_backend += `sort=${sort}&`;
    if(filter) url_backend += `filter=${filter}&`;
    if(from) url_backend += `from=${from}&`;
    if(to) url_backend += `to=${to}&`;

    // chiamata http al backend per estarrre i dati degli eventi dal db
    fetch(url_backend)
        .then(res => res.json())
        .then(data =>{
            const boxEvento = document.getElementById("boxEvento");
            boxEvento.innerHTML = "";
            data.forEach(evento => {
                boxEvento.innerHTML += `
                    <div class="evento">
                        <div id="event-msg-${evento.id}" class=""></div>
                        <h3>${evento.titolo}</h3>

                        <div class="evento-body">
                            <p><strong>Stato:</strong> ${evento.stato.charAt(0).toUpperCase() + evento.stato.slice(1)}</p>
                            <p><strong>Data:</strong> ${evento.data}</p>
                            <p><strong>Orario:</strong> ${evento.orario}</p>
                        </div>

                        <div class="evento-actions">
                            <button onclick="eliminaEvento(${evento.id})">
                                Elimina
                            </button>
                            
                            ${evento.stato == "in programma" ? `
                                <button onclick="apriModifica({
                                                    id: ${evento.id},
                                                    titolo: '${evento.titolo}',
                                                    data: '${evento.data}',
                                                    stato: '${evento.stato}',
                                                    orario: '${evento.orario}'
                                                })">
                                        Modifica
                                </button>
                                <button onclick="concludiEvento(${evento.id}, '${evento.data}')">
                                    Concludi
                                </button>
                            ` : ""}

    
                        </div>
                    </div>
                `;
            });
        });
}

function eliminaEvento(id_evento){
    if(!confirm("Sei sicuro di voler eliminare questo evento?")){
        return;
    }
    fetch("../backend/functions/delete_event.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + id_evento
    })
    .then(res => res.json())
    .then(response => {

        if(response.success){

            showToast(response.message, "success");
            mostraEventi();

        } else {

            showMessage(response.message, "error");
        }

    });
}

function concludiEvento(id_evento, data_evento) {

    const oggi = new Date();
    oggi.setHours(0,0,0,0);

    const data = new Date(data_evento);
    const erroreEevnto = document.getElementById(`event-msg-${id_evento}`);


    if (data > oggi) {
        let messaggio = "Evento Futuro! Impossibile da concludere!";
        showEventMessage(id_evento,messaggio,"error");
        return;
    }

    fetch("../backend/functions/close_event.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id=" + id_evento
    })
    .then(res => res.json())
    .then(response => {

        if (response.success) {
            showMessage(response.message);
            mostraEventi();
        } else {
            erroreEevnto.innerHTML = response.message;
            setTimeout(() => {
                erroreEevnto.innerHTML = "";
            }, 3000);
        }

    });
}


function showMessage(text, tipo) {
    
    const box = document.getElementById("Esito");

    if(tipo == "success"){
        box.innerHTML = `<div class="success-message">${text}</div>`;
    }else{
        box.innerHTML = `<div class="error-message">${text}</div>`;
    }

    setTimeout(() => {
        box.innerHTML = "";
    }, 3000);
}



//messaggio che viene visualizzato all'interno della card di un evento
function showEventMessage(id_evento, messaggio, tipo){
    const div = document.getElementById(`event-msg-${id_evento}`);

    if(!div) return;

    div.className = `event-message-${tipo}`;
    div.innerHTML = messaggio;

    setTimeout(() => {
        div.innerHTML = "";
        div.className = "";
    }, 3000);
}

function showToast(message, type = "success") {

    const toast = document.getElementById("toast");

    if (!toast) return;

    toast.textContent = message;
    toast.className = `toast ${type} show`;

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}



function salvaModifica() {

    const id = getEditingEventId();

    const titolo = document.getElementById("editTitolo").value.trim();
    const data = document.getElementById("editData").value;
    const orario = document.getElementById("editOra").value;

    if (!titolo || !data|| !orario) {
        showModalError("Tutti i campi devono essere compilati");
        return;
    }

    fetch("../backend/functions/update_event.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `id=${id}&titolo=${encodeURIComponent(titolo)}&data=${data}&ora=${orario}`
    })
    .then(res => res.json())
    .then(response => {

        if (response.success) {
            chiudiModifica();
            mostraEventi();
            showToast(response.message, "success");

        } else {
            showModalError(response.message);
        }

    });
}

let editingEventId = null;

function apriModifica(evento) {

    editingEventId = evento.id;

    document.getElementById("editTitolo").value = evento.titolo;
    document.getElementById("editData").value = evento.data;
    document.getElementById("editOra").value = evento.orario;

    document.getElementById("modalEdit").classList.remove("hidden");

    clearModalError();
}


function chiudiModifica() {

    document.getElementById("modalEdit").classList.add("hidden");

    editingEventId = null;

    clearModalError();
}


function getEditingEventId() {
    return editingEventId;
}


function showModalError(message) {

    const box = document.getElementById("modalError");

    if (!box) return;

    box.innerHTML = `
        <div class="error-message">
            ${message}
        </div>
    `;
}


function clearModalError() {

    const box = document.getElementById("modalError");

    if (box) {
        box.innerHTML = "";
    }
}