<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="public/styles/main.css" rel="stylesheet">
    <link href="public/styles/dashboard.css" rel="stylesheet">
    <link href="public/styles/utility/fonts.css" rel="stylesheet">
    <link href="public/styles/utility/UI.css" rel="stylesheet">

    <title>Sign In</title>
</head>

<body id="dashboard-page" class="flex-row-center-center">
    <h1>Panel główny</h1>

    <div class="dashboard-box">
        <p><strong>Pracownicy:</strong> <?= htmlspecialchars($employee_count) ?></p>
        <p><strong>Godziny (ten miesiąc):</strong> <?= htmlspecialchars($total_hours) ?></p>
        <p><strong>Wypłaty:</strong> <?= htmlspecialchars($payroll_count) ?></p>
        <p><strong>Zatwierdzone wypłaty:</strong> <?= htmlspecialchars($approved_payrolls) ?></p>
    </div>

    <div class="dashboard-links">
        <a href="/employees">Zarządzaj pracownikami</a><br>
        <a href="/payrolls">Historia wypłat</a><br>
        <a href="/export">Eksport danych</a><br>
    </div>


</body>

</html>