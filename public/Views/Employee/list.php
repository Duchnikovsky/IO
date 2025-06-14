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

    <title>Employees list</title>
</head>

<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <!-- nagłówek + przycisk -->
    <div class="list-header">
        <h1>Twoi pracownicy</h1>
        <a href="/add" class="add-btn">+ Dodaj pracownika</a>
    </div>

    <?php if (empty($employees)) : ?>
        <p class="no-data">Brak pracowników. Dodaj pierwszego!</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Stawka godzinowa</th>
                    <th style="width:80px"></th> <!-- kolumna na kosz -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?= htmlspecialchars($employee['first_name']) ?></td>
                        <td><?= htmlspecialchars($employee['last_name']) ?></td>
                        <td><?= htmlspecialchars($employee['hourly_rate']) ?> zł/h</td>
                        <td>
                            <form action="/delete" method="POST" onsubmit="return confirm('Na pewno chcesz usunąć tego pracownika?')">
                                <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
                                <button type="submit" class="trash-btn">🗑️</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="/dashboard" class="back-link">← Wróć do dashboardu</a>
</body>

</html>