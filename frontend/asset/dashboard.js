document.addEventListener("DOMContentLoaded", function(){
    mostraEventi();

    const eventForm = document.getElementById("eventForm");

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

    const oggi = new Date().toISOString().split("T")[0];

    if(stato.value == 2 && data.value > oggi){

        showError("Un evento futuro non può essere concluso");

    }
}

function mostraEventi(){
    // chiamata http al backend per estarrre i dati degli eventi dal db
    fetch("../backend/functions/fetch_list_event.php")
        .then(res => res.json())
        .then(data =>{
            const boxEvento = document.getElementById("boxEvento");
            boxEvento.innerHTML = "";
            data.forEach(evento => {
                boxEvento.innerHTML += `
                    <div style="border:1px solid #ccc; padding:10px; margin:5px;">
                        <h3>${evento.titolo}</h3>
                        <p>${evento.data}</p>
                        <p>${evento.stato}</p>
                    </div>
                `;
            });
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