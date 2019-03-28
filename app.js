const months = [
    [31],
    [28],
    [31],
    [30],
    [31],
    [30],
    [31],
    [31],
    [30],
    [31],
    [30],
    [31]
];
const monthsD = [
    ["Janvier"],
    ["Février"],
    ["Mars"],
    ["Avril"],
    ["Mai"],
    ["Juin"],
    ["Juillet"],
    ["Aout"],
    ["Septembre"],
    ["Octobre"],
    ["Novembre"],
    ["Decembre"]
];
var days = 0;
for (var m in months) days += Number(months[m]);

function convertDateToNumber(day, month) {
    day = Number(day);
    month = Number(month);
    let dayCount = 0;
    for (i = 0; i < month; i++) {
        dayCount += Number(months[i]);
    }

    dayCount-=Number(months[month-1]);
    dayCount+=day;
    return dayCount;
}

function convertNumberToDay(num) {
    let dayCount = 0;
    let month = 1;
    for (i = 0; i < num; i++) {
        dayCount++;
        if (months[month-1] == dayCount) {
            month++;
            dayCount = 0;
        }
    }
    console.log(month);
    return {day: dayCount, month: month};
}

function getDayName(day, month) {
    if (day > 31 || day < 1 || month > 12 || month < 1) return "NaN";
    dayCount = 0;
    for (i = 0; i < month; i++) {
        dayCount += Number(months[i]);
    }

    dayCount-=Number(months[month-1]);
    dayCount+=day;
    console.log((dayCount)%7);

    switch((dayCount)%7) {
        case 0 : return "Lundi"; break;
        case 1 : return "Mardi"; break;
        case 2 : return "Mercredi"; break;
        case 3 : return "Jeudi"; break;
        case 4 : return "Vendredi"; break;
        case 5 : return "Samedi"; break;
        case 6 : return "Dimanche"; break;
    }
}
console.log("Le 10/06 tombe un " + getDayName(10, 6)); // ok fixé


const event = [
    ["Création du template HTML", [0, 0], [0, 0]],
    ["Ajout du JavaScript", [9, 1], [12, 1]],
    ["Création du MCD", [13, 1], [11, 2]]
];
let monthD = 0;
let dayR = 0;
for (var day=1; day < days+1; day++) {
    dayR++;
    if (dayR > months[monthD]){
        monthD += 1;
        dayR = 1;
    }
    $( "#date" ).append(`<TD>${dayR < 10 ? '0'+dayR : dayR }</TD>`);
}

for (var mn=0; mn < monthsD; mn++) {
    dayR++;
    if (dayR > months[monthD]){
        monthD += 1;
        dayR = 1;
    }
    $( "#date" ).append(`<TD>${dayR < 10 ? '0'+dayR : dayR }</TD>`);
}

function addEtape(tabIndex, etapeIndex) {
    $('#tab').append(`<TR id='objectif_${tabIndex}'><TD>${etapeIndex[0]}</TD></TR>`);
    isSet = false;
    for (var day=1; day < days+1; day++)
        if (convertDateToNumber(etapeIndex[1][0], etapeIndex[1][1]) > day || convertDateToNumber(etapeIndex[2][0], etapeIndex[2][1]) < day)
            $( "#objectif_"+tabIndex ).append(`<TD id="${tabIndex}_${day}" style='background-color:white;'> </TD>`); //+
        else
        if (!isSet) {
            $( "#objectif_"+tabIndex ).append(`<TD id="${tabIndex}_${day}" style='background-color:green;' COLSPAN=${convertDateToNumber(etapeIndex[2][0], etapeIndex[2][1]) - convertDateToNumber(etapeIndex[1][0], etapeIndex[1][1])+1}></TD>`);
            isSet = true;
        }
}

function addObj(objID, name) {
    $('#tab').append(`<TR id='objectif_${objID}'><TD>${name}</TD></TR>`);
}

function initRow(month, tabIndex) {
    $('#tab').append(`<TR id='addEtape_${tabIndex}'><TD style="background-color:#e6b13a;" id="newEtapeText"> <b>Ajouter une étape ... </b></TD></TR>`);
    for (var day=1; day < days+1; day++)
            $( "#addEtape_"+tabIndex ).append(`<TD id="add_${day}" style='background-color:white;' onClick="markadd(${tabIndex}, ${day})"> + </TD>`);

}

//addEtape(1, 0);
//addEtape(2, 1);
//addEtape(3, 2);
function add(startDate, endDate, id, text) {
    $( "#"+id ).append(`<TD COLSPAN=${startDate-1}></TD>`);
    //for (var day=startDate; day < endDate; day++) {
    //console.log(day);
    $( "#"+id ).append(`<TD COLSPAN=${endDate-startDate} style="background-color:red;">${text}</TD>`);
    //}
}

add(5,15,"etape_1_1", "Jérôme");
add(8,17,"etape_1_2", "Jérôme");
add(10,25,"etape_1_3", "Dorian");

let catIndex = 0;
let dayStart = 0;
let dayEnd = 0;

function markadd(index, day) {
        if (dayStart == day){
            dayStart = dayEnd;
            dayEnd = 0;
            for (i = 0; i <= days; i++) $("#add_"+i).css('background-color', '#FFFFFF');
            $("#add_"+dayStart).css('background-color', '#e07542');
            console.log("Le jour de start est " + dayStart + " et le jour de fin remis à zero")
        }
        else if (dayEnd == day){
            $("#add_"+dayEnd).css('background-color', '#FFFFFF');
            dayEnd = 0;
            for (i = 0; i <= days; i++) $("#add_"+i).css('background-color', '#FFFFFF');
            $("#add_"+dayStart).css('background-color', '#e07542');
            console.log("Le jour de end est remit à zero")
        }
        else if (day > dayStart) {
            if (dayEnd == 0 && dayStart == 0) {
                dayStart = day;
                $("#add_"+dayStart).css('background-color', '#e07542');
            }else {
                $("#add_"+dayEnd).css('background-color', '#FFFFFF');
                dayEnd = day;
                console.log("Le jour de fin est " + dayEnd)
                for (i = 0; i <= days; i++) $("#add_"+i).css('background-color', '#FFFFFF');
                $("#add_"+dayStart).css('background-color', '#e07542');
                for (i = dayStart; i <= dayEnd; i++) $("#add_"+i).css('background-color', '#e07542');
            }
        }else {
            if (dayStart != 0 && day < dayStart && dayEnd == 0) {
                dayEnd = dayStart;
            }else $("#add_"+dayStart).css('background-color', '#FFFFFF'); // On cache la case car dayEnd ne la prend pas car dayEnd != 0
            dayStart = day;
            for (i = 0; i <= days; i++) $("#add_"+i).css('background-color', '#FFFFFF');
            for (i = dayStart; i <= dayEnd; i++) $("#add_"+i).css('background-color', '#e07542');
            console.log("Le jour de départ a changé, il s'agit du jour " + dayStart)
            if (dayEnd > 0) {
                let duration = dayEnd - dayStart;
            }
        }

        if(dayStart > 0 && dayEnd > 0) {
            $("#dateEtape").html("L'étape a lieu du jour <b> "+dayStart+"</b> au jour <b> "+dayEnd+"</b> soit <b> "+(dayEnd-dayStart+1)+"</b> jours !");
            dataS = convertNumberToDay(dayStart);
            dataE = convertNumberToDay(dayEnd);
            $("#dateEtapeS").val("2019-"+dataS.month+"-"+dataS.day+" 00:00:00");
            $("#dateEtapeE").val("2019-"+dataE.month+"-"+dataE.day+" 00:00:00");
            $("#dateEtapeS").html("2019-"+dataS.month+"-"+dataS.day+" 00:00:00");
            $("#dateEtapeE").html("2019-"+dataE.month+"-"+dataE.day+" 00:00:00");
        }
        else if(dayStart == 0) {
            $("#dateEtape").html("<b>Vous devez choisir une case pour le début de la date !</b>");
            $("#dateEtapeS").val("");
            $("#dateEtapeE").val("");
        }
        else if(dayEnd == 0) {
            $("#dateEtape").html("<b>Vous devez choisir une case pour la fin pour la date !</b>");
            $("#dateEtapeS").val("");
            $("#dateEtapeE").val("");
        }
    //console.log(index + " - " + day)
}
