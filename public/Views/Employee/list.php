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

    <h1>Twoi pracownicy</h1>

    <?php if (empty($employees)) : ?>
        <p>Brak pracowników. Dodaj pierwszego!</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>Stawka godzinowa</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?= htmlspecialchars($employee['first_name']) ?></td>
                        <td><?= htmlspecialchars($employee['last_name']) ?></td>
                        <td><?= htmlspecialchars($employee['hourly_rate']) ?> zł/h</td>
                        <form action="/employees/delete" method="POST" style="display:inline;">
                            <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">
                            <button type="submit" onclick="return confirm('Na pewno chcesz usunąć tego pracownika?')">🗑️</button>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="/dashboard">← Wróć do dashboardu</a>

</body>

</html>