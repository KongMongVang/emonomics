# EmoNomics

**Author:** Kong Vang Mong  
**Course:** Capstone - HTTP-5310-0NA

---

**Admin Login:**
- Email: `admin@example.com`
- Password: `admin1234`

---

EmoNomics is a personal finance dashboard that helps you understand the connection between your emotions and spending habits. Built with Laravel, it features dynamic emotion and category management, admin/user dashboards, and a clean, modern UI.

## Features
- **User Dashboard:** Track your transactions, spending by category, and see visual insights into your emotional spending patterns.
- **Emotion Tracking:** Record your mood (emotion) with every transaction. Add, edit, and delete your own moods. Admins can manage global moods and view all user-created moods.
- **Category Management:** Organize your spending by customizable categories.
- **Admin Dashboard:** View platform-wide stats, manage users, categories, and all moods (emotions).
- **Authentication:** Secure login, registration, and user suspension features.
- **Responsive UI:** Modern, mobile-friendly design using Tailwind CSS.

## How It Works
1. **Record Transactions:** Each time you spend, log the amount, category, and your emotion (e.g., happy, stressed, bored, etc.).
2. **Visual Insights:** The dashboard shows which moods lead to the most spending, helping you spot emotional spending triggers.
3. **Self-Awareness:** Use these insights to set better budgets, recognize emotional triggers, and build healthier money habits.

## Getting Started
1. **Clone the repository:**
   ```sh
   git clone <your-repo-url>
   cd capstone-economics/economics
   ```
2. **Install dependencies:**
   ```sh
   composer install
   npm install
   ```
3. **Set up environment:**
   - Copy `.env.example` to `.env` and configure your database settings.
   - Run `php artisan key:generate`
4. **Run migrations and seeders:**
   ```sh
   php artisan migrate --seed
   ```
5. **Start the development server:**
   ```sh
   php artisan serve
   npm run dev
   ```
6. **Access the app:**
   - Visit [http://localhost:8000](http://localhost:8000)

## Project Structure
- `app/Models/Emotion.php` - Emotion model (moods)
- `app/Http/Controllers/EmotionController.php` - Handles mood CRUD logic
- `resources/views/` - Blade templates for dashboard, admin, auth, etc.
- `routes/web.php` - All web routes
- `database/migrations/` - Database schema

## About
Have you ever wondered why your bank account is empty at the end of the month, even though you don't remember making any major purchases? The answer might be hiding in your emotions. EmoNomics helps you understand the connection between your emotions and spending habits, revealing patterns you never knew existed.

## License
MIT
