<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cookie&family=Playwrite+GB+S:ital,wght@0,100..400;1,100..400&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

<?php
// Ustawienia nagłówka dla wydruku i stylu tabeli
echo "<style>
    table { 
        width: 100%; 
        table-layout: fixed; /* Wszystkie kolumny mają tę samą szerokość */
        border-collapse: collapse; 
        font-size: 12pt; 
        font-family: Roboto;
    }
    th, td {
        border: 1px solid black; 
        padding: 10px; 
        text-align: left;
        width: 14.28%; /* Każda kolumna ma dokładnie 1/7 szerokości (100% podzielone przez 7) */
        height: 100px;
        vertical-align: top;
    }
    th {
        background-color: #f2f2f2;
        height: 20px;
    }
    .grey {
        color: #d0d0d0; /* Szary kolor dla dni spoza bieżącego miesiąca */
    }
    @page {
        size: A4 landscape;
        margin: 10mm;
    }
    h2 {
        font-family: 'Playwrite GB S';
        font-size: 40px;
        text-align: center;
        position: relative;
    }
    .nav-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 30px; /* Rozmiar strzałek */
        color: #007BFF; /* Kolor strzałek */
        text-decoration: none; /* Brak podkreślenia */
    }
    .prev {
        left: 10px; /* Pozycjonowanie lewego przycisku */
    }
    .next {
        right: 10px; /* Pozycjonowanie prawego przycisku */
    }
</style>";

// Funkcja zwracająca nazwy miesięcy po polsku
function getPolishMonthName($month) {
    $months = [
        1 => 'Styczeń', 2 => 'Luty', 3 => 'Marzec', 4 => 'Kwiecień', 
        5 => 'Maj', 6 => 'Czerwiec', 7 => 'Lipiec', 8 => 'Sierpień', 
        9 => 'Wrzesień', 10 => 'Październik', 11 => 'Listopad', 12 => 'Grudzień'
    ];
    return $months[$month];
}

// Sprawdzanie, czy w URL podano month i year, w przeciwnym razie używany jest bieżący miesiąc i rok
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Obliczanie poprzedniego i następnego miesiąca
$previousMonth = ($month == 1) ? 12 : $month - 1;
$previousYear = ($month == 1) ? $year - 1 : $year;
$nextMonth = ($month == 12) ? 1 : $month + 1;
$nextYear = ($month == 12) ? $year + 1 : $year;

// Tworzymy obiekt DateTime dla pierwszego dnia danego miesiąca
$firstDayOfMonth = new DateTime("$year-$month-01");
// Pobieramy liczbę dni w miesiącu
$daysInMonth = (int)$firstDayOfMonth->format('t');

// Tworzymy obiekt DateTime dla poprzedniego miesiąca, aby uzyskać liczbę dni
$firstDayOfPreviousMonth = (clone $firstDayOfMonth)->modify('-1 month');
$daysInPreviousMonth = (int)$firstDayOfPreviousMonth->format('t');

// Wyświetlanie przycisków nawigacyjnych
echo "<h2>";
echo "<a href='?month=$previousMonth&year=$previousYear' class='nav-arrow prev'>&lt;</a>"; // Strzałka w lewo
echo getPolishMonthName($month) . " " . $year; // Nazwa miesiąca i roku
echo "<a href='?month=$nextMonth&year=$nextYear' class='nav-arrow next'>&gt;</a>"; // Strzałka w prawo
echo "</h2>";

echo "<table>";
// Generowanie nagłówka z dniami tygodnia
echo "<tr>";
$daysOfWeek = ['Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela'];
foreach ($daysOfWeek as $day) {
    echo "<th>$day</th>";
}
echo "</tr>";

// Pobieramy pierwszy dzień tygodnia w miesiącu (1 = poniedziałek, 7 = niedziela)
$firstDayOfWeek = (int)$firstDayOfMonth->format('N');

// Tworzenie wierszy z dniami miesiąca
echo "<tr>";

// Dni z poprzedniego miesiąca
for ($i = $firstDayOfWeek - 1; $i > 0; $i--) {
    $day = $daysInPreviousMonth - $i + 1;
    echo "<td class='grey'>$day</td>";
}

// Dni z bieżącego miesiąca
for ($i = 1; $i <= $daysInMonth; $i++) {
    $date = new DateTime("$year-$month-$i");
    $dayOfWeek = (int)$date->format('N'); // Numer dnia tygodnia (1 = poniedziałek, 7 = niedziela)

    // Wyświetlanie dnia
    echo "<td>$i</td>";

    // Zakończ wiersz po niedzieli (dzień 7)
    if ($dayOfWeek == 7) {
        echo "</tr><tr>"; // Nowy wiersz
    }
}

// Dni z następnego miesiąca (uzupełnienie ostatniego rzędu)
$remainingDays = 7 - (int)(new DateTime("$year-$month-$daysInMonth"))->format('N');
for ($i = 1; $i <= $remainingDays; $i++) {
    echo "<td class='grey'>$i</td>";
}

echo "</tr>";
echo "</table>";
?>
