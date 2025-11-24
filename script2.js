// 1. Selectăm elementele
const btn = document.getElementById('btnDetalii');
const detaliiContainer = document.getElementById('detalii');
const spanData = document.getElementById('dataProdus');


detaliiContainer.classList.add('ascuns');

const luni = [
    "Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie",
    "Iulie", "August", "Septembrie", "Octombrie", "Noiembrie", "Decembrie"
];
const now = new Date();
spanData.textContent = `${now.getDate()} ${luni[now.getMonth()]} ${now.getFullYear()}`;


btn.addEventListener('click', function() {
    
    detaliiContainer.classList.toggle('ascuns');

    if (detaliiContainer.classList.contains('ascuns')) {
        btn.textContent = "Afișează detalii";
        btn.style.backgroundColor = "#0071e3"; 
    } else {
        btn.textContent = "Ascunde detalii";
        btn.style.backgroundColor = "#333"; 
    }
});