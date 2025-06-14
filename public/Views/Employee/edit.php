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

    <title>Edit Employee</title>
</head>

<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>
    <h1>Edytuj pracownika</h1>

    <?php if (isset($employee)): ?>
        <form method="POST" action="/edit">
            <input type="hidden" name="id" value="<?= htmlspecialchars($employee['id']) ?>">

            <div class="employee-input">
                <label for="first_name">Imie</label>
                <input id="first_name" type="text" name="first_name" value="<?= htmlspecialchars($employee['first_name']) ?>" required placeholder="Wprowadź imię pracownika">
            </div>

            <div class="employee-input">
                <label for="last_name">Nazwisko</label>
                <input id="last_name" type="text" name="last_name" value="<?= htmlspecialchars($employee['last_name']) ?>" required placeholder="Wprowadź nazwisko pracownika">
            </div>

            <div class="employee-input">
                <label for="hourly_rate">Stawka godzinowa</label>
                <input id="hourly_rate" type="text" name="hourly_rate" value="<?= htmlspecialchars($employee['hourly_rate']) ?>" required placeholder="Wprowadź stawke godzinową">
            </div>

            <button type="submit">Zapisz zmiany</button>
        </form>
    <?php else: ?>
        <p>Nie znaleziono pracownika.</p>
    <?php endif; ?>

    <a href="/employees">← Wróć do listy</a>

</body>

</html>