<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="public/styles/main.css" rel="stylesheet">
    <link href="public/styles/dashboard.css" rel="stylesheet">
    <link href="public/styles/utility/fonts.css" rel="stylesheet">
    <link href="public/styles/utility/UI.css" rel="stylesheet">
    <link href="public/styles/employee/employee.css" rel="stylesheet">

    <title>Add Employee</title>
</head>

<body>

    <h1>Dodaj nowego pracownika</h1>

    <form action="/employees/add" method="POST" class="employee-form">
        <div class="employee-input">
            <label for="name">Imie</label>
            <input id="name" type="text" name="name" class="input" required placeholder="Wprowadź imię pracownika">
        </div>
        <div class="employee-input">
            <label for="surname">Nazwisko</label>
            <input id="surname" type="text" name="surname" class="input" required placeholder="Wprowadź nazwisko pracownika">
        </div>
        <div class="employee-input">
            <label for="hourly_rate">Stawka godzinowa (zł)</label>
            <input id="hourly_rate" type="number" name="hourly_rate" class="input" step="0.01" min="0" required placeholder="Wprowadź stawkę godzinową">
        </div>

        <button type="submit">Dodaj pracownika</button>
    </form>

    <a href="/employees">← Wróć do listy</a>

</body>

</html>