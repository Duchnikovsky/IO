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
    <?php include __DIR__ . '/../partials/header.php'; ?>
    <h1>Dodaj nowego pracownika</h1>

    <form action="/add" method="POST" class="employee-form">
        <div class="employee-input">
            <label for="first_name">Imie</label>
            <input id="first_name" type="text" name="first_name" class="input" required placeholder="Wprowadź imię pracownika">
        </div>
        <div class="employee-input">
            <label for="last_name">Nazwisko</label>
            <input id="last_name" type="text" name="last_name" class="input" required placeholder="Wprowadź nazwisko pracownika">
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