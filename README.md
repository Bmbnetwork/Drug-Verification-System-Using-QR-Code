# Drug Management System with QR Code Verification

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?logo=mysql)
![XAMPP](https://img.shields.io/badge/XAMPP-Compatible-E34F26?logo=xampp)
![License](https://img.shields.io/badge/License-MIT-green)

A web-based Drug Management System that enables registration and verification of essential drugs (antibiotics, anti-infective, and anti-malarial medications) with unique QR code generation for authenticated products.

## 📋 Features

### User Features
- ✅ User registration and authentication
- ✅ Drug product registration with complete details
- ✅ Real-time product status tracking (Pending/Accepted/Rejected)
- ✅ QR code access for verified products

### Admin Features
- ✅ Product review and approval workflow
- ✅ Accept/Reject drug registrations with reasons
- ✅ Automatic QR code generation for approved drugs
- ✅ Email notifications (configurable)

### System Features
- 📱 **Responsive Design** - Works on all devices
- 🔒 **Role-based Access Control** - Separate user/admin interfaces
- 📊 **Complete Drug Information** - Company, Manufacturer, Drug Name, Batch Number
- 🖼️ **Local QR Code Verification** - Manual entry verification (no external dependencies)
- 💾 **MySQL Database** - Secure data storage
- 🏠 **XAMPP Compatible** - Ready for localhost development

## 🚀 Installation

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) installed
- Basic knowledge of PHP/MySQL

### Step-by-Step Setup

1. **Download and Extract**
   ```bash
   # Clone or download the project files
   # Extract to your XAMPP htdocs directory
   C:\xampp\htdocs\drug-management-system\
   ```

2. **Enable GD Library**
   - Open `C:\xampp\php\php.ini`
   - Find `;extension=gd` and remove the semicolon
   - Save and restart Apache

3. **Database Setup**
   - Start Apache and MySQL in XAMPP Control Panel
   - Visit `http://localhost/phpmyadmin`
   - Create database: `drug_management`
   - Import the provided SQL schema

4. **Folder Structure**
   Ensure these directories exist:
   ```
   drug-management-system/
   ├── assets/
   ├── includes/
   ├── admin/
   ├── user/
   ├── qrcode/          # (will store generated QR codes)
   └── vendor/phpqrcode/ # (QR code library)
   ```

5. **Test Installation**
   - Visit `http://localhost/drug-management-system/`
   - Login as admin: `admin` / `admin123`

## 👥 User Guide

### For Regular Users
1. **Register** an account
2. **Login** and access your dashboard
3. **Register Product** with complete drug information:
   - Drug Name & Type (antibiotic/anti-infective/anti-malarial)
   - Company & Manufacturer
   - Batch Number & Expiry Date
4. **Monitor Status** - Check if your product is Pending/Accepted/Rejected
5. **Access QR Codes** - Download QR codes for approved products

### For Administrators
1. **Login** with admin credentials
2. **Review Products** in the management dashboard
3. **Approve/Reject** submissions with optional reasons
4. **Monitor Statistics** - Track total products and approval rates

### Drug Verification
- **Manual Entry**: Visit verification page and enter QR code content exactly as:  
  `DRUG|Company|Manufacturer|DrugName|BatchNumber`
- **Smartphone Scan**: Use phone camera to scan QR codes (redirects automatically)

## 🛠️ Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+
- **Database**: MySQL
- **QR Code Library**: PHP QR Code
- **Web Server**: Apache (via XAMPP)
- **Local Development**: XAMPP compatible

## 📁 Project Structure

```
drug-management-system/
├── assets/              # CSS and JavaScript files
├── includes/            # Database and function files
├── admin/               # Admin dashboard pages
├── user/                # User dashboard pages
├── qrcode/              # Generated QR code images
├── vendor/phpqrcode/    # QR code generation library
├── index.php            # Home page
├── login.php            # Authentication
├── register.php         # User registration
├── verify-qr.php        # Drug verification page
└── logout.php           # Single logout handler
```

## 🔒 Security Notes

- **Local Development**: Designed for localhost testing
- **Password Hashing**: Uses PHP's `password_hash()` function
- **SQL Injection Prevention**: Uses PDO prepared statements
- **Input Validation**: Server-side validation on all forms
- **Session Management**: Secure session handling

> **Important**: For production deployment, implement HTTPS, configure email notifications, and enhance security measures.

## 🚫 Limitations

- **Drug Types**: Only antibiotics, anti-infective, and anti-malarial drugs
- **Exclusions**: No herbal or locally made medicines
- **No Mobile App**: Uses existing smartphone camera functionality
- **Local Verification**: Manual entry required (no image-based QR decoding)

## 📄 Documentation

- **[User Manual](docs/user-manual.md)** - Complete user guide
- **[Database Schema](docs/database-schema.sql)** - Table structure
- **[API Endpoints](docs/api.md)** - Available endpoints (if applicable)

## 🤝 Contributing

Contributions are welcome! Please follow these steps:
1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- PHP QR Code library by Dominik Dzienia
- XAMPP development environment
- All contributors and users

## Credit 
- Bilal Mohammed Bello (BMB NETWORK)
- Nexus Web Labs
- bmbnetwork1@gmail.com
---

**Ready to use out of the box with XAMPP!** 🚀

For support or questions, please open an issue in the repository.
