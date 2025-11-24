// 1. Selectăm elementele din DOM (Document Object Model)
const inputActivitate = document.getElementById('inputActivitate');
const btnAdauga = document.getElementById('btnAdauga');
const listaActivitati = document.getElementById('listaActivitati');

const luni = [
    "Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie",
    "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie"
];

btnAdauga.addEventListener('click', function() {
    
    const textActivitate = inputActivitate.value.trim();

    if (textActivitate !== "") {
        
        const elementNou = document.createElement('li');

        const dataCurenta = new Date();
        
        const ziua = dataCurenta.getDate(); // 1-31
        const lunaIndex = dataCurenta.getMonth(); // 0-11 (0 = Ianuarie)
        const anul = dataCurenta.getFullYear(); // yyyy

        const numeLuna = luniAnului[lunaIndex];

       
        const textAfisat = `${textActivitate} – adăugată la: ${ziua} ${numeLuna} ${anul}`;

        elementNou.textContent = textAfisat;

        listaActivitati.appendChild(elementNou);

        inputActivitate.value = "";
        
        inputActivitate.focus();

    } else {
        alert("Te rog introdu o activitate validă!");
    }
});