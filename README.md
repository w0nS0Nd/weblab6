# Backend Labs 6 — Laravel + Keycloak (JWT) — Готовий шаблон

Цей репозиторій містить:
- **Laravel API** з інтеграцією пакета `robsontenorio/laravel-keycloak-guard` (JWT перевірка токена).
- **Мідлвар для ролей** (`ProductsApiViewer`, `ProductsApiWriter`) на рівні клієнтських ролей Keycloak.
- **CRUD /products** із перевіркою ролей.
- **OpenAPI** (`/docs/openapi.yaml`) із потоками `authorization_code` та `client_credentials`.
- **Postman колекція** для швидкого тестування.
- **Keycloak realm export** з клієнтом `subscriber-app` і ролями.

> Вендор не включено. Після клонування виконайте `composer install`.

## Стек / версії
- Laravel 10/11 сумісний (guard працює однаково).
- Keycloak 24/25 (OIDC).

---

## 1) Запуск Keycloak
```bash
cd keycloak
docker compose up -d
# імпорт відбудеться автоматично (див. --import-realm)
```

Адмін-консоль: http://localhost:8080/ (логін/пароль: `admin` / `admin` — для локальної розробки).  
Realm: **lab-realm**.  
Клієнт: **subscriber-app** (confidential), дозволені потоки: auth-code + client-credentials.  
Ролі (client roles): **ProductsApiViewer**, **ProductsApiWriter**.

> Якщо консоль не доступна: перевірте `docker compose logs keycloak`.

---

## 2) Підготовка Laravel
```bash
cd laravel-app
cp .env.example .env
composer install
php artisan key:generate
php artisan serve --host=0.0.0.0 --port=8000
```

У файлі `.env`:
- `KEYCLOAK_REALM_PUBLIC_KEY` — скопіюйте з Keycloak: *Realm Settings → Keys → RS256 → Public Key*.
- `KEYCLOAK_ALLOWED_RESOURCES=subscriber-app` (має збігатися з clientId у Keycloak).

> Guard і конфіг уже додані в `config/auth.php` і `config/keycloak.php` (опублікована версія).

---

## 3) Отримання токенів

### 3.1 Authorization Code (через браузер або Postman)
Authorization URL:
```
http://localhost:8080/realms/lab-realm/protocol/openid-connect/auth
```
Token URL:
```
http://localhost:8080/realms/lab-realm/protocol/openid-connect/token
```
Client: `subscriber-app` (confidential), Redirect URI: `http://localhost:8000/callback/*` і `https://oauth.pstmn.io/v1/callback` (для Postman).

Увійдіть користувачем **viewer** (має роль *ProductsApiViewer*) або **writer** (має *ProductsApiWriter*).

### 3.2 Client Credentials
```bash
# замініть CLIENT_SECRET фактичним секретом клієнта subscriber-app
curl -X POST http://localhost:8080/realms/lab-realm/protocol/openid-connect/token   -H "Content-Type: application/x-www-form-urlencoded"   -d "grant_type=client_credentials"   -d "client_id=subscriber-app"   -d "client_secret=CLIENT_SECRET"
```

---

## 4) Перевірка API
```bash
# Публічний ping
curl http://localhost:8000/api/ping

# Список товарів (потрібна роль ProductsApiViewer або Writer)
curl http://localhost:8000/api/products   -H "Authorization: Bearer YOUR_ACCESS_TOKEN"

# Створити товар (потрібна роль ProductsApiWriter)
curl -X POST http://localhost:8000/api/products   -H "Authorization: Bearer YOUR_ACCESS_TOKEN"   -H "Content-Type: application/json"   -d '{"name":"Phone","price":999}'
```

---

## 5) OpenAPI + Postman
- `docs/openapi.yaml` — готова схема з двома потоками: `authorization_code` та `client_credentials`.
- `docs/postman_collection.json` — імпортуйте в Postman, відредагуйте `client_secret` в Variables.

---
