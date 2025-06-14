<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Rejestracja godzin pracy</title>

    <link href="/public/styles/main.css" rel="stylesheet">
    <link href="/public/styles/dashboard.css" rel="stylesheet">
    <link href="/public/styles/utility/fonts.css" rel="stylesheet">
    <link href="/public/styles/utility/UI.css" rel="stylesheet">
    <link href="/public/styles/employee/employee.css" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <h1>Zarejestruj godziny pracy</h1>

    <?php if (isset($employee)): ?>
        <form method="POST" action="/logHours" class="employee-form">
            <input type="hidden" name="employee_id" value="<?= htmlspecialchars($employee['id']) ?>">

            <div class="employee-input">
                <label for="date">Data</label>
                <input id="date" type="date" name="date" class="input" required>
            </div>

            <div class="employee-input">
                <label for="hours">Liczba godzin</label>
                <input id="hours" type="number" name="hours" class="input" step="0.25" min="0" required>
            </div>

            <button type="submit">Zapisz</button>
        </form>
    <?php else: ?>
        <p>Nie znaleziono pracownika.</p>
    <?php endif; ?>

    <a href="/employees">← Wróć do listy</a>

</body>

</html>