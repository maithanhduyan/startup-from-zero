# Copilot Instructions for startup-from-zero

## Project Overview
This is an infrastructure-as-code repository for startup services using Docker Compose and Traefik reverse proxy. The project provides production-ready configurations for:
- **Traefik v3**: Reverse proxy with automatic Let's Encrypt SSL
- **PostgreSQL 16**: Database with persistent storage
- **pgAdmin**: Database management UI
- **Open WebUI**: AI chat interface (connects to Ollama)
- **System tools**: fail2ban, logrotate, webmin configurations

## Architecture & Key Patterns

### Network Architecture
- All services connect to a shared external Docker network named `company-net`
- Create the network before starting services: `docker network create company-net`
- Services communicate internally via Docker DNS (e.g., `postgres:5432`, `ollama:11434`)

### Traefik Integration Pattern
Services expose themselves via Traefik labels, not ports. Example from [open-webui/docker-compose.yml](open-webui/docker-compose.yml):
```yaml
labels:
  - "traefik.enable=true"
  - "traefik.http.routers.open-webui.rule=Host(`${OPEN_WEBUI_HOST}`)"
  - "traefik.http.routers.open-webui.tls=true"
  - "traefik.http.routers.open-webui.tls.certresolver=letsencrypt"
  - "traefik.http.routers.open-webui.entrypoints=websecure"
  - "traefik.http.services.open-webui.loadbalancer.server.port=8080"
```

### Configuration Management
- Each service directory has `.env.sample` showing required environment variables
- Copy `.env.sample` to `.env` and customize before deployment
- Actual `.env` files are git-ignored for security
- All services use `env_file: .env` for configuration

### File Persistence Pattern
- Service data directories are mounted as volumes: `./data:/var/lib/postgresql/data`
- Traefik stores Let's Encrypt certificates in `./acme.json` (chmod 600)
- Logs are stored in service-specific directories (e.g., `traefik/log/`)

## Critical Workflows

### Starting Services
```bash
# 1. Create shared network (one time)
docker network create company-net

# 2. Configure environment for each service
cd <service-directory>
cp .env.sample .env
nano .env  # Edit with actual values

# 3. Start Traefik first (handles SSL/routing)
cd traefik && docker compose up -d

# 4. Start other services
cd ../postgresql && docker compose up -d
cd ../open-webui && docker compose up -d
```

### Traefik SSL Setup
- Ensure `acme.json` has correct permissions: `chmod 600 traefik/acme.json`
- Let's Encrypt uses HTTP challenge on port 80
- Certificates auto-renew; check status in dashboard at `${TRAEFIK_HOST}`
- Dashboard protected by Basic Auth: `${TRAEFIK_USERNAME}:${TRAEFIK_PASSWORD}` (htpasswd format)

### Database Operations
PostgreSQL runs in [postgresql/](postgresql/) with credentials from `.env`:
```bash
# Connect to PostgreSQL
docker exec -it postgres psql -U ${POSTGRES_USER} -d ${POSTGRES_DB}

# Backup database
docker exec postgres pg_dump -U ${POSTGRES_USER} ${POSTGRES_DB} > backup.sql

# Restore database
docker exec -i postgres psql -U ${POSTGRES_USER} -d ${POSTGRES_DB} < backup.sql
```

### System Security (fail2ban)
- Configurations in [fail2ban/README.md](fail2ban/README.md) show SSH jail setup
- Ban action: UFW firewall blocks after 3 failed attempts for 24h
- Check status: `sudo fail2ban-client status sshd`
- Unban IP: `sudo fail2ban-client set sshd unbanip <IP>`

### Log Management (logrotate)
- Custom configs in [logrotate/README.md](logrotate/README.md)
- Default: weekly rotation, keep 4 backups
- Apply custom config: `sudo cp /home/logrotate/custom-configs/<file> /etc/logrotate.d/`
- Test: `sudo logrotate -d /etc/logrotate.conf`

## Project-Specific Conventions

### Docker Compose Style
- All services use `container_name:` for easy reference
- `restart: unless-stopped` for production resilience
- Health checks where available (see [traefik/docker-compose.yml](traefik/docker-compose.yml#L42-L47))
- JSON logging with rotation: `max-size: "100m"`, `max-file: "3"`
- Timezone explicitly set: `TZ=UTC`

### Domain/Host Configuration
- Services expect subdomains: `traefik.your-company.com`, `chat.your-company.com`
- Update `.env` with actual domain before deployment
- Traefik handles all SSL termination; backend services run HTTP

### Multi-language Documentation
- README files include Vietnamese (Tiếng Việt) commands and descriptions
- Configuration examples show production-ready values (not defaults)
- Command references include most common operations, not exhaustive lists

## Integration Points

### Open WebUI ↔ Ollama
- Open WebUI expects Ollama at `http://ollama:11434`
- Both must be on `company-net` network
- Ollama service not included in this repo (deploy separately)

### pgAdmin ↔ PostgreSQL
- pgAdmin connects to PostgreSQL via `postgres:5432` on `company-net`
- Data directory must have correct permissions: `chown -R 5050:5050 pgadmin/pgadmin/`
- Health check uses pgAdmin's ping endpoint: `/misc/ping`
- Access via subdomain defined in `${PGADMIN_HOST_NAME}`

### Traefik ↔ All Services
- Traefik watches Docker socket: `/var/run/docker.sock:/var/run/docker.sock:ro`
- Services with `traefik.enable=true` are auto-discovered
- Static configuration in [traefik/dynamic.yml](traefik/dynamic.yml) (currently empty)

## Common Pitfalls
- **Network not created**: Services won't start without `docker network create company-net`
- **acme.json permissions**: Must be 600 or Traefik fails SSL
- **Missing .env files**: Copy from `.env.sample` before starting services
- **Port conflicts**: Only Traefik exposes ports (80/443); don't expose service ports
- **Wrong domain**: Update all `*_HOST` variables in .env files before deployment
- **pgAdmin permissions**: Data directory needs UID 5050: `chown -R 5050:5050 pgadmin/pgadmin/`

## When Adding New Services
1. Create service directory with `docker-compose.yml`
2. Add `networks: company-net: external: true`
3. Create `.env.sample` with all required variables
4. If web-accessible, add Traefik labels (no port exposure)
5. Set container name, restart policy, timezone, logging
6. Document in README.md with Vietnamese translation
