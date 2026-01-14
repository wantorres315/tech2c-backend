# Tech2C Backend — Laravel 11 + Docker

# Tech2C Backend (Laravel API)

Backend API for the Tech2C platform, responsible for processing Excel files exported by DGEG and generating environmental indicators related to CO₂ emissions and energy consumption.

This project was developed for the **Tech2C Junior / Mid Fullstack Engineer Challenge**.

⚠️ Language Notice  
Although this README is written in English, the API and the data are in Portuguese because the original DGEG Excel file is in Portuguese. Sector names such as *Energia, Transportes, Indústria, Agricultura, Serviços* are preserved exactly as in the source file.

---

## 🐳 Running with Docker

The backend is fully dockerized and should be run using Docker Compose.

Inside the backend directory:

## 🚀 Como iniciar

```bash
cp .env.example .env
docker compose up -d
docker compose exec app php artisan key:generate
