document.addEventListener("DOMContentLoaded", function(){
    mostraEventi();

    const eventForm = document.getElementById("eventForm");

    eventForm.addEventListener("submit", function(e){
        e.preventDefault();
        const data = new FormData(this);

        fetch("../backend/functions/create_event.php", {
            method:"POST",
            body: data
        })
        .then(res => res.text())
        .then(() => {
            this.reset();
            mostraEventi();
            showMessage("Evento registrato con successo");
        });
    });
});



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