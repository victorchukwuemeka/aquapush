# aquaPush 🚀

**aquaPush** is a web app that makes it simple to deploy your Laravel applications to [DigitalOcean](https://www.digitalocean.com) without touching the command line.  
Instead of juggling endless setup steps, aquaPush automates the flow for you—just connect your repo, provide your DigitalOcean API key and SSH key, and we’ll handle the rest.  

## ✨ Features
- One-click deployment of Laravel projects to DigitalOcean droplets
- Automatic server provisioning (Apache, PHP, MySQL, Composer, etc.)
- Secure environment file setup (`.env` auto-configuration)
- GitHub repo integration for quick project cloning
- Automated permissions & Laravel setup (`key:generate`, `migrate`, etc.)
- Error logging for smoother troubleshooting

## 🔑 Requirements (Current)
- A **DigitalOcean account** with an **API Key**
- An **SSH key** for secure server access

*(We’re working on making this flow even smoother, with less manual setup in the future.)*

## 🌐 Why aquaPush?
Deploying Laravel apps to DigitalOcean can be tricky, especially for developers who don’t want to deal with server configs. aquaPush bridges that gap—just provide your repo, API key + SSH key, and let aquaPush do the heavy lifting.  

## 🚧 Roadmap
- CI/CD support (auto-deploy on push)
- Automatic SSH management (no need to manually add keys)
- Custom domain management
- SSL setup
- Team & billing features
- Pre-configured stack for scaling Laravel apps

## 💡 Status
Currently in active development. First version focuses on **droplet creation + Laravel deployment using API + SSH keys**.  
Future versions will make the process much smoother, removing the need for manual SSH key management.

