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

    <form method="POST" action="/logHours" class="employee-form">
        <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">

        <div class="callendar-grid">
            <?php foreach ($days as $day): ?>
                <div class="callendar-cell">
                    <label><?= $day->format('d.m') ?></label>
                    <input type="number"
                        step="0.25"
                        min="0"
                        name="hours[<?= $day->format('Y-m-d') ?>]"
                        class="hours-input"
                        value="<?= $existing[$day->format('Y-m-d')] ?? '' ?>">
                </div>
            <?php endforeach; ?>
        </div>

        <br>
        <button type="submit">Zapisz wszystkie</button>
    </form>

    <a href="/employees">← Wróć do listy</a>

</body>

</html>