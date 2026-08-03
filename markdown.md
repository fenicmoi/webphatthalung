# 🚀 Project AI Guidelines: Modern Web Application with CodeIgniter 4.3.8

## 📌 1. Tech Stack & Environment Restrictions
- **Backend Framework**: CodeIgniter 4.3.8 (Strictly compatible with PHP 7.4). Do not introduce features that require PHP 8.0+.
- **Database**: MySQL 5.7+ / MariaDB 10.4+ using CI4 Query Builder and Models.
- **Frontend Core**: HTML5, Vanilla CSS / Custom Styles, Vanilla JavaScript (Modern ES6+, Fetch API, Async-Await) or Alpine.js / Vue.js.
- **CSS Framework**: Bootstrap 5.3+ or Tailwind CSS (integrated natively with custom aesthetic enhancements).
- **Target OS & Server**: Linux (DirectAdmin Hosting) / Local WAMP Development.

---

## 🎨 2. UI/UX & Aesthetics Philosophy (CRITICAL REQUIREMENTS)
Whenever designing interfaces or presenting views, you MUST adhere to Modern & Premium design best practices:
1. **Rich & Dynamic Aesthetics**: 
   - Avoid plain or dated government web themes. Ensure the design feels state-of-the-art, visually striking, and intuitive.
   - Use vibrant, harmonious color schemes, smooth gradients, and elegant box shadows.
2. **Glassmorphism & Depth**:
   - Utilize contemporary glass-like UI cards with transluscent backgrounds and blur effects (e.g., `background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.2);`).
3. **Animations & Micro-interactions**:
   - Add gentle hover transformations and smooth CSS transitions (e.g., `transition: all 0.25s ease-in-out;`). 
   - Interactive components (buttons, dropdowns, modal windows, tab switches) should respond smoothly without jarring cuts.
4. **Dark & Light Mode Toggle**:
   - Structure CSS utilizing CSS custom properties (variables) or CSS framework themes to effortlessly support dynamic Dark/Light mode switching without reloading the page.
5. **Modern Typography**:
   - Apply highly legible, contemporary font families (such as 'Sarabun', 'Prompt', 'Inter', or 'Outfit') with clear font hierarchy.

---

## ⚡ 3. Interactive Frontend & Architecture Standards
To create a reactive, seamless app-like user experience without heavy frameworks:
1. **No-Reload Data Fetching (SPA Feel)**:
   - Perform form submissions, search filterings, dynamic paginations, and modal content loading asynchronously using **Vanilla JS (Fetch API with Async/Await)**, **Alpine.js**, or **Vue.js**.
   - DO NOT rely on full page reloads (`window.location` or traditional `<form action="..." method="POST">`) for standard interactions unless explicitly requested for whole-page redirects.
2. **Seamless Backend Communication**:
   - Backend API endpoints created in CodeIgniter 4 should cleanly return structured **JSON responses** with proper HTTP status codes (`200 OK`, `400 Bad Request`, `404 Not Found`, `500 Internal Server Error`).
   - All asynchronous Fetch/AJAX requests MUST attach and update the **CI4 CSRF Security Token** in HTTP Headers or Request Payload to ensure robust security.
3. **User Feedback & Loading States**:
   - Always integrate instant feedback during asynchronous calls (e.g., skeleton loading screens, smooth loading spinners, or modern toast alerts using SweetAlert2).

---

## 🛠️ 4. CodeIgniter 4 Coding Conventions
1. **Namespace & Autoloading**: Strictly utilize PSR-4 namespaces in all Controllers, Models, Filters, and Libraries. Do NOT use classic CI3 `load->model()` syntax.
2. **Model Usage**: Implement `CodeIgniter\Model` cleanly, specifying `$table`, `$primaryKey`, `$allowedFields`, and validation rules natively within Model classes.
3. **Views Structure**: Keep views organized in `app/Views/` utilizing CI4 View Layouts (`$this->extend()`, `$this->section()`, `$this->renderSection()`) for highly reusable HTML templates.
4. **Clean Code & Thai Context**: Ensure comments, variables, and structural design are clean, well-organized, and adapted for Thai language content handling (`utf-8` and mbstring compatibility).
