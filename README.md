# 🚀 EBANX Challenge - Laravel API

Este é um projeto desenvolvido como parte do desafio técnico da **EBANX**, onde foi implementada uma **API REST** utilizando **Laravel**.

---

## 📌 Tecnologias Utilizadas
✔ **PHP 8.2**  
✔ **Laravel 10**  
✔ **PHPUnit** para testes automatizados  
✔ **NGROK** para exposição da API  

---

## 📦 Instalação e Configuração

### **1️⃣ Clonar o repositório**
```bash
git clone https://github.com/gabee97/ebanx-challenge.git
cd ebanx-challenge
```

### **2️⃣ Instalar as dependências**
```bash
composer install
```

### **3️⃣ Criar o arquivo `.env`**
```bash
cp .env.example .env
```

### **4️⃣ Gerar a chave da aplicação**
```bash
php artisan key:generate
```

### **5️⃣ Iniciar o servidor local**
```bash
php artisan serve
```
🔹 A API estará disponível em **http://127.0.0.1:8000**.

### **6️⃣ Expor a API via NGROK (opcional)**
```bash
ngrok http 8000
```
🔹 Isso gerará uma **URL pública** para acesso.

---

## 🛠️ **Testes Automatizados**
Para validar se a API está funcionando corretamente, execute:

```bash
php artisan test --filter AccountTest
```

---

## 📬 Contato
Caso tenha alguma dúvida ou sugestão, fico à disposição!  

✉ **[gaabriel123@hotmail.com](mailto:gaabriel123@hotmail.com)**  
💼 **[LinkedIn - Gabriel Oliveira](https://www.linkedin.com/in/gabriel-oliveira-gop1997/)**  
🚀 **Desenvolvido por Gabriel Oliveira**  
