# Payroll App – Dokumentacja projektowa

## Spis treści

1. Wprowadzenie i cel projektu
2. Kontekst biznesowy i aktorzy
3. Wymagania funkcjonalne i jakościowe
4. Architektura systemu (C4)
5. Model domenowy – Event‑Storming
6. Struktura bazy danych i migracje
7. Uruchomienie środowiska Docker
8. Road‑mapa rozwoju
9. Podsumowanie

---

### 1. Wprowadzenie, cel i misja projektu

Payroll App to lekka aplikacja webowa dla mikro‑ i małych firm, której celem jest ułatwienie ewidencji pracowników, rejestrowania godzin oraz automatycznego obliczania wypłat. Projekt demonstruje pełny cykl wytwórczy:

- **Big‑Picture Event Storming** – dekompozycja domeny,
- modelowanie **C4** (kontenery, komponenty),
- implementacja w **czystym PHP 8.2** (MVC),
- pełna konteneryzacja **Docker Compose**,
- idempotentne migracje **PostgreSQL**.

### Cele biznesowe:
| Nazwa                     | Opis                                          | Kryteria akceptacyjne                        |
| ------------------------- | --------------------------------------------- | -------------------------------------------- |
| Automatyzacja rozliczeń   | Skrócenie czasu tworzenia wypłat o 80%        | Wypłaty liczone automatycznie z godzin       |
| Historia i przejrzystość  | Umożliwienie przeglądu wszystkich operacji    | Lista pracowników, godziny, wypłaty widoczne |
| Niskie koszty użytkowania | Wersja offline lub hostowana lokalnie/darmowo | Brak opłat miesięcznych                      |


### Cele klienta:
| Nazwa              | Opis                                         | Kryteria akceptacyjne                 |
| ------------------ | -------------------------------------------- | ------------------------------------- |
| Intuicyjna obsługa | Prosty interfejs, bez konieczności szkolenia | Dodanie pracownika < 1 minuta         |
| Prywatność danych  | Każdy użytkownik widzi tylko swoje dane      | Filtracja po `user_id`                |
| Mobilność          | Możliwość korzystania z telefonu / tabletu   | UI działa poprawnie w mobilnej wersji |


<b>Misja:</b>
Stworzyć prostą, lekką i dostępną aplikację webową dla małych firm, która umożliwia zarządzanie pracownikami, rejestrację czasu pracy oraz automatyczne generowanie i zatwierdzanie wypłat.

→ Interaktywny diagram w Miro: [Board](https://miro.com/welcomeonboard/bHpkUy90cHBqaVhEeHZKVXlRWUpMeTRvaDZzOFVjcFJJZFp6TDRoY3JMcU96MGZDUWJNSzhqU2lnZWxUbWlzc2dabmwrNEdGRTlaSWFLMDZWMDVkNTQ0Ukp5anhDc0p6SmtCMTJ2TGFiRHdHcTlUNTR5cEVibW0yR0Rsd1NlV0N0R2lncW1vRmFBVnlLcVJzTmdFdlNRPT0hdjE=?share_link_id=172719750683)

---

### 2. Kontekst biznesowy i aktorzy

| Aktor          | Motywacja / cele                                         |
| -------------- | -------------------------------------------------------- |
| Menadżer firmy | Dodaje pracowników, zatwierdza wypłaty, pobiera raporty. |
| Pracownik      | Dostarcza przepracowane godziny (pośrednio).             |
| System bankowy | Otrzymuje zlecenie wypłaty i zwraca status _paid_.       |


### Analiza istniejących rozwiązań
| Narzędzie          | Plusy                                   | Minusy                                         |
| ------------------ | --------------------------------------- | ---------------------------------------------- |
| **Symfonia ERP**   | zaawansowane funkcje płacowe            | zbyt skomplikowane i kosztowne dla małych firm |
| **Excel**          | prostota, elastyczność                  | brak walidacji, brak historii, ręczne liczenie |
| **Toggl Track**    | dobre do rejestracji czasu              | brak wypłat i integracji płacowych             |
| **moja aplikacja** | prostota, pełna kontrola, niskie koszty | ograniczone funkcje podatkowe                  |



---

### 3. Wymagania

#### 3.1 Funkcjonalne (MVP)

1. Logowanie i wylogowanie użytkownika (sesje).
2. CRUD pracowników: _add → list → delete_.
3. Rejestracja godzin (etap II).
4. Obliczanie wypłaty: **suma godzin × stawka**.
5. Statusy wypłaty: _draft → approved → paid_.
6. Eksport raportu **PDF/CSV**.
7. Log zdarzeń w tabeli **events**.

#### 3.2 Jakościowe

- Responsywne UI (CSS Variables, brak frameworka).
- Jedno‑poleceniowe uruchomienie: `docker compose up`.
- Idempotentny skrypt `setup.php` – tworzy tabele tylko raz.
- Gotowość pod **event sourcing** (kolumna `payload` JSONB).

---

### 4. Architektura (C4)

| Kontener       | Technologia       | Kluczowa odpowiedzialność    |
| -------------- | ----------------- | ---------------------------- |
| Front‑end SPA  | HTML, JS          | UI, komunikacja REST         |
| API / Back‑end | PHP 8.2‑FPM (MVC) | Logika domenowa, autoryzacja |
| DB             | PostgreSQL 16     | Persistencja danych          |
| PgAdmin        | dpage/pgadmin4    | GUI bazy (dev‑ops)           |

---

### 5. Model domenowy – Event‑Storming

| Komenda          | Zdarzenie         | Agregat  |
| ---------------- | ----------------- | -------- |
| AddEmployee      | EmployeeAdded     | Employee |
| LogHours         | HoursLogged       | WorkLog  |
| CalculatePayroll | PayrollCalculated | Payroll  |
| ApprovePayroll   | PayrollApproved   | Payroll  |
| PayPayroll       | PayrollPaid       | Payroll  |

---

### 6. Struktura bazy danych i migracje

```sql
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE employees (
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(100),
    last_name  VARCHAR(100),
    hourly_rate NUMERIC(10,2),
    user_id INT REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE work_logs (
    id SERIAL PRIMARY KEY,
    employee_id INT REFERENCES employees(id) ON DELETE CASCADE,
    date DATE,
    hours_worked NUMERIC(5,2)
);

CREATE TABLE payrolls (
    id SERIAL PRIMARY KEY,
    employee_id INT REFERENCES employees(id) ON DELETE CASCADE,
    from_date DATE, to_date DATE,
    total_hours NUMERIC(5,2),
    total_payment NUMERIC(10,2),
    is_approved BOOLEAN DEFAULT FALSE,
    is_paid BOOLEAN DEFAULT FALSE
);

CREATE TABLE events (
    id SERIAL PRIMARY KEY,
    entity_type VARCHAR(50),
    entity_id INT,
    event_type VARCHAR(100),
    payload JSONB,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

_Skrypt `src/setup.php` tworzy powyższe tabele automatycznie._

---

### 7. Uruchomienie środowiska Docker

```bash
# 1. Skonfiguruj sekrety
cp .env.example .env            # uzupełnij hasła Postgres

# 2. Buduj i startuj
docker compose up --build       # pierwszy start ≈ 30 s

# 3. Wejdź na
http://localhost:8080            # logowanie
http://localhost:5050            # PgAdmin (opcjonalnie)
```

Kontener PHP **czeka na bazę** (`pg_isready`), uruchamia migracje i startuje FPM; Nginx reverse proxy obsługuje routing (`rewrite → /public/index.php`).

---

### 8. Road‑mapa rozwoju

- Moduł _work‑logs_ (widok kalendarza + import CSV).
- Rozbudowany algorytm payroll (bonusy, nadgodziny, podatki).
- Middleware **CSRF + JWT API** (dla SPA).
- **CI/CD – GitHub Actions**: testy + build obrazu.
- Pełny **Event Sourcing** + projekcje read‑modeli.

---

### 9. Podsumowanie

Projekt demonstruje pełny proces inżynierii oprogramowania: od **Event‑Stormingu** przez model **C4** i implementację **MVC**, aż po działające środowisko kontenerowe. Kod jest lekki, łatwy do rozwijania.

---
