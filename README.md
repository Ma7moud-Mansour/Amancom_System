# Amancom GPS Management System

\
*A web-based system for managing GPS devices, SIM cards, customers, subscriptions, and sales.*

## Overview

Amancom GPS Management System is a web application designed to streamline the management of GPS tracking devices, SIM cards, customer accounts, subscriptions, and sales for Amancom. Built with a focus on simplicity, performance, and Arabic RTL support, the system runs efficiently on Raspberry Pi 4 (2GB RAM) with a 16-inch touchscreen display. It provides a modern dashboard, inventory tracking, subscription management, and integration capabilities with Odoo for seamless business operations.

### Features

- **Dashboard**: Real-time overview of inventory, customers, subscriptions, and alerts.
- **Inventory Management**: Track GPS devices and SIM cards with statuses (available, assigned, inactive).
- **Customer Management**: Manage customer profiles and link them to devices and subscriptions.
- **Subscriptions**: Monitor subscription statuses (active, expiring, expired) and renewals.
- **Sales & Payments**: Record sales transactions and payment statuses.
- **Alerts**: Dynamic notifications for critical events (e.g., low inventory, expiring subscriptions).
- **Odoo Integration**: Sync customer and sales data with Odoo ERP (optional).
- **RTL Support**: Fully compatible with Arabic right-to-left interface.
- **Performance**: Optimized for low-resource devices like Raspberry Pi 4.

## Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla JS, Chart.js for visualizations)
- **Backend**: PHP 7.4+
- **Database**: MySQL 8.0+
- **Server**: Apache or Nginx
- **Hardware**: Raspberry Pi 4 (2GB RAM) with 16-inch touchscreen
- **Fonts**: IBM Plex Sans Arabic for clean Arabic typography

## Prerequisites

- Raspberry Pi 4 (2GB RAM) running Raspberry Pi OS or a similar Linux distribution.
- Apache/Nginx web server with PHP 7.4+.
- MySQL 8.0+ database server.
- Internet connection for CDN dependencies (FontAwesome, Chart.js, Google Fonts).
- Web browser (Chrome, Firefox, or Edge).

## Installation

1. **Clone the Repository**:

   ```bash
   git clone https://github.com/Ma7moud-Mansour/Amancom_System.git
   cd amancom-gps
   ```

2. **Set Up the Web Server**:

   - Copy the project files to your web server's root directory (e.g., `/var/www/html` for Apache).
   - Ensure the server has PHP and MySQL modules enabled.

   ```bash
   sudo apt update
   sudo apt install apache2 php libapache2-mod-php php-mysql mysql-server
   ```

3. **Configure the Database**:

   - Create a MySQL database named `amancom`.
   - Import the schema from `database/schema.sql`:

     ```bash
     mysql -u root -p amancom < database/schema.sql
     ```
   - Update database credentials in `config/db.php`:

     ```php
     <?php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'your_username');
     define('DB_PASS', 'your_password');
     define('DB_NAME', 'amancom');
     ?>
     ```

4. **Install Dependencies**:

   - No additional installations are required as the project uses CDN-hosted libraries (Chart.js, FontAwesome, Google Fonts).

5. **Run the Application**:

   - Start the web server:

     ```bash
     sudo systemctl start apache2
     ```
   - Open `http://localhost` or the Raspberry Pi's IP address in a browser.
   - Log in with default credentials (admin/admin) and change them immediately.

## Usage

- **Dashboard**: View real-time stats for devices, SIM cards, customers, and subscriptions.
- **Add Device**: Navigate to "إضافة جهاز" to register new GPS devices with serial numbers and SIM assignments.
- **Manage Inventory**: Use the inventory table to filter and update device statuses.
- **Alerts**: Monitor toast notifications for urgent events (e.g., expiring subscriptions).
- **Odoo Sync**: Enable Odoo integration in `config/odoo.php` to sync sales and customer data (optional).

## Contributing

We welcome contributions to improve Amancom GPS Management System! To contribute:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/your-feature`).
3. Commit your changes (`git commit -m "Add your feature"`).
4. Push to the branch (`git push origin feature/your-feature`).
5. Open a Pull Request with a clear description of your changes.

Please ensure your code follows:

- Clean, commented code.
- RTL compatibility for Arabic interfaces.
- Performance optimization for Raspberry Pi.

## License

This project is licensed under the MIT License. See the LICENSE file for details.

## Contact

For questions or support, contact:

- **Email**: support@amancom.com
- **GitHub Issues**: Open an issue

---

Built with love by the Amancom Team (Mahmoud Abdelkareem, Ahmed Salah, Omar Hesham And Ahmed Ebrahim).