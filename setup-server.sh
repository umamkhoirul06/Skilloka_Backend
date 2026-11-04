#!/bin/bash
# =============================================================================
# Skilloka Server Setup Script
# Jalankan: bash setup-server.sh
# Server: umam@172.198.131.133
# =============================================================================

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Skilloka Server Setup - skilloka.my.id${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# --- STEP 1: Update sistem ---
echo -e "${YELLOW}[1/7] Update sistem...${NC}"
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl git nano htop

# --- STEP 2: Install Docker ---
echo -e "${YELLOW}[2/7] Install Docker...${NC}"
if ! command -v docker &> /dev/null; then
    curl -fsSL https://get.docker.com | sudo sh
    sudo usermod -aG docker $USER
    sudo systemctl enable docker
    sudo systemctl start docker
    echo -e "${GREEN}Docker terinstall!${NC}"
else
    echo -e "${GREEN}Docker sudah ada: $(docker --version)${NC}"
fi

# --- STEP 3: Setup Nginx Proxy Manager ---
echo -e "${YELLOW}[3/7] Setup Nginx Proxy Manager...${NC}"
mkdir -p ~/nginx-proxy-manager
cat > ~/nginx-proxy-manager/docker-compose.yml << 'EOF'
version: '3.8'

services:
  npm:
    image: jc21/nginx-proxy-manager:latest
    container_name: nginx-proxy-manager
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
      - "81:81"
    volumes:
      - npm_data:/data
      - npm_letsencrypt:/etc/letsencrypt
    networks:
      - proxy_network

networks:
  proxy_network:
    name: proxy_network
    driver: bridge

volumes:
  npm_data:
  npm_letsencrypt:
EOF

cd ~/nginx-proxy-manager
docker compose up -d
echo -e "${GREEN}Nginx Proxy Manager berjalan!${NC}"

# --- STEP 4: Setup SSH Key untuk GitHub Actions ---
echo -e "${YELLOW}[4/7] Setup SSH Deploy Key...${NC}"
if [ ! -f ~/.ssh/deploy_key ]; then
    ssh-keygen -t ed25519 -C "github-actions-skilloka" -f ~/.ssh/deploy_key -N ""
    cat ~/.ssh/deploy_key.pub >> ~/.ssh/authorized_keys
    chmod 600 ~/.ssh/authorized_keys
    echo ""
    echo -e "${RED}============================================================${NC}"
    echo -e "${RED}PENTING! Copy private key ini ke GitHub Secret SERVER_SSH_KEY:${NC}"
    echo -e "${RED}============================================================${NC}"
    cat ~/.ssh/deploy_key
    echo -e "${RED}============================================================${NC}"
    echo ""
fi

# --- STEP 5: Setup aplikasi Skilloka ---
echo -e "${YELLOW}[5/7] Update docker-compose.yml aplikasi...${NC}"
cd ~/skilloka_backend

# Backup docker-compose lama
cp docker-compose.yml docker-compose.yml.backup 2>/dev/null || true

# --- STEP 6: Setup .env production ---
echo -e "${YELLOW}[6/7] Cek file .env...${NC}"
if [ ! -f .env ]; then
    cp .env.example .env
    echo -e "${YELLOW}File .env dibuat dari .env.example - EDIT SEBELUM DEPLOY!${NC}"
fi

# --- STEP 7: Tampilkan ringkasan ---
echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  Setup Selesai!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "📋 ${YELLOW}Langkah selanjutnya:${NC}"
echo ""
echo -e "1. ${GREEN}Edit .env production:${NC}"
echo "   nano ~/skilloka_backend/.env"
echo "   (Ubah: APP_DEBUG=false, DB credentials, MEILISEARCH_KEY, dll)"
echo ""
echo -e "2. ${GREEN}Tambah GitHub Secrets:${NC}"
echo "   SERVER_HOST    = 172.198.131.133"
echo "   SERVER_USER    = umam"
echo "   SERVER_PORT    = 22"
echo "   SERVER_SSH_KEY = (private key yang ditampilkan di atas)"
echo "   GHCR_TOKEN     = (GitHub Personal Access Token dengan scope: read:packages)"
echo ""
echo -e "3. ${GREEN}Arahkan DNS skilloka.my.id ke 172.198.131.133${NC}"
echo ""
echo -e "4. ${GREEN}Buka NPM Admin UI:${NC} http://172.198.131.133:81"
echo "   Login: admin@example.com / changeme"
echo "   Tambah Proxy Host: skilloka.my.id → 172.198.131.133:8080"
echo "   Aktifkan SSL Let's Encrypt"
echo ""
echo -e "5. ${GREEN}Push ke main branch untuk trigger deploy pertama:${NC}"
echo "   git push origin main"
echo ""
echo -e "6. ${GREEN}Atau deploy manual pertama kali:${NC}"
echo "   cd ~/skilloka_backend"
echo "   docker compose up -d"
echo "   sleep 20"
echo "   docker compose exec -T app php artisan migrate --force"
echo "   docker compose exec -T app php artisan optimize"
echo ""
echo -e "${GREEN}URL Admin NPM: http://172.198.131.133:81${NC}"
echo -e "${GREEN}App setelah deploy: https://skilloka.my.id${NC}"

