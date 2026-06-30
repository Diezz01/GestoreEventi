document.addEventListener("DOMContentLoaded", function(){
    mostraEventi();

    const eventForm = document.getElementById("eventForm");

    document.getElementById("applyFilters").addEventListener("click", function(){
        mostraEventi();
    });

    const data = document.querySelector("[name='data']");
    const stato = document.querySelector("[name='stato']");
        
    stato.addEventListener("change", function(){
        checkEvento(data, stato);
    });

    data.addEventListener("change", function(){
        checkEvento(data, stato);
    });

    eventForm.addEventListener("submit", function(e){
        e.preventDefault();
        const formData = new FormData(this);

        fetch("../backend/functions/create_event.php", {
            method:"POST",
            body: formData
        })
        .then(res => res.json())
        .then(response => {
            //ricevo la risposta dal backend
            if(response.success){

                this.reset();
                mostraEventi();

                showMessage(response.message);

            } else {

                showError(response.message);

            }
        });
    });
});

// controllo dai input del form prima di registrae un evento
function checkEvento(data, stato){

    const oggi = new Date();
    oggi.setHours(0,0,0,0);

    const dataEvento = new Date(data.value);

    console.log("controllo");
    console.log(stato.value);

    //un evento futuro non puo esere concluso
    if (parseInt(stato.value) === 2 && dataEvento > oggi) {

        console.log("non va bene");

        showError("Un evento futuro non può essere concluso");

        return false;
    }

    //un evento passato non puo essere in programma
    if (parseInt(stato.value) === 1 && dataEvento < oggi) {
        showError("Un evento passato non può essere messo in programma");
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
                        <div id="error-${evento.id}" class=""></div>
                        <h3>${evento.titolo}</h3>

                        <div class="evento-body">
                            <p><strong>Stato:</strong> ${evento.stato.charAt(0).toUpperCase() + evento.stato.slice(1)}</p>
                            <p><strong>Data:</strong> ${evento.data}</p>
                        </div>

                        <div class="evento-actions">
                            <button onclick="eliminaEvento(${evento.id})">
                                    Elimina
                            </button>
                            ${evento.stato == "in programma" ? `
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

            showMessage(response.message);
            mostraEventi();

        } else {

            showError(response.message);
        }

    });
}

function concludiEvento(id_evento, data_evento) {

    const oggi = new Date();
    oggi.setHours(0,0,0,0);

    const data = new Date(data_evento);
    const erroreEevnto = document.getElementById(`error-${id_evento}`);


    if (data > oggi) {

        erroreEevnto.classList.add("event-error");

        erroreEevnto.innerHTML = "Evento Futuro! Impossibile da concludere!";

        setTimeout(() => {
            erroreEevnto.innerHTML = "";
            erroreEevnto.classList.remove("event-error");
        }, 3000);

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


function showMessage(text) {

    const box = document.getElementById("Esito");

    box.innerHTML = `<div class="success-message">${text}</div>`;

    setTimeout(() => {
        box.innerHTML = "";
    }, 3000);
}

function showError(text){

    const box = document.getElementById("Esito");

    box.innerHTML = `
        <div class="error-message">
            ${text}
        </div>
    `;

    setTimeout(() => {
        box.innerHTML = "";
    },3000);
}
