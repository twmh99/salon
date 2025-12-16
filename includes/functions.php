<?php
/**
 * Helper functions untuk DBeauty Skincare & Day Spa
 */
require_once __DIR__ . '/../config/config.php';

/** Sanitize input string */
function sanitize(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

/** Simple flash message handler */
function flash(string $key, ?string $message = null)
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return;
    }

    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }

    return null;
}

function authCustomer(): ?array
{
    return $_SESSION['customer'] ?? null;
}

function authAdmin(): ?array
{
    return $_SESSION['admin'] ?? null;
}

function requireCustomerAuth(): void
{
    if (!authCustomer()) {
        flash('error', 'Silakan login terlebih dahulu.');
        header('Location: /customer/login.php');
        exit;
    }
}

function requireAdminAuth(): void
{
    if (!authAdmin()) {
        flash('error', 'Session admin diperlukan.');
        header('Location: /admin/login.php');
        exit;
    }
}

function getTreatments(): array
{
    static $treatments = null;
    if ($treatments === null) {
        $treatments = require __DIR__ . '/treatments.php';
    }
    return $treatments;
}

/** Membantu mendapatkan detail treatment berdasarkan kode */
function findTreatment(string $code): ?array
{
    foreach (getTreatments() as $item) {
        if ($item['id'] === $code) {
            return $item;
        }
    }
    return null;
}

define('RESERVATION_STATUSES', ['pending', 'confirmed', 'cancelled', 'completed']);

function fetchCount(string $sql, array $params = []): int
{
    $row = getSingleRow($sql, $params);
    return (int)($row['total'] ?? 0);
}
