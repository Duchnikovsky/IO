<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Lista wypłat</title>
    <link href="public/styles/main.css" rel="stylesheet">
    <link href="public/styles/dashboard.css" rel="stylesheet">
    <link href="public/styles/utility/fonts.css" rel="stylesheet">
    <link href="public/styles/utility/UI.css" rel="stylesheet">
    <link href="public/styles/employee/employee.css" rel="stylesheet">
</head>

<body>

    <?php include __DIR__ . '/../partials/header.php'; ?>

    <h1>Lista wypłat</h1>

    <table>
        <thead>
            <tr>
                <th>Pracownik</th>
                <th>Okres</th>
                <th>Godzin</th>
                <th>Kwota</th>
                <th>Status</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payrolls as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?></td>
                    <td><?= $p['from_date'] ?> → <?= $p['to_date'] ?></td>
                    <td><?= $p['total_hours'] ?></td>
                    <td><?= number_format($p['total_payment'], 2) ?> zł</td>
                    <td>
                        <?php if ($p['is_paid']): ?>
                            <span style="color: green;">💸 wypłacone</span>
                        <?php elseif ($p['is_approved']): ?>
                            <span style="color: orange;">✅ zatwierdzone</span>
                        <?php else: ?>
                            <span style="color: gray;">📝 draft</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!$p['is_approved']): ?>
                            <form method="POST" action="/updateStatus" style="display:inline;">
                                <input type="hidden" name="payroll_id" value="<?= $p['id'] ?>">
                                <input type="hidden" name="action" value="approve">
                                <button type="submit">Zatwierdź</button>
                            </form>
                        <?php endif; ?>
                        <?php if ($p['is_approved'] && !$p['is_paid']): ?>
                            <form method="POST" action="/updateStatus" style="display:inline;">
                                <input type="hidden" name="payroll_id" value="<?= $p['id'] ?>">
                                <input type="hidden" name="action" value="pay">
                                <button type="submit">Oznacz jako wypłacone</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>