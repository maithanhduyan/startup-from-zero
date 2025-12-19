# Fail2ban Configuration Backup

## Thư mục này chứa:

| File | Mô tả |
|------|-------|
| `jail.local.backup` | Backup của file cấu hình hiện tại |
| `jail.conf.reference` | File tham khảo gốc từ Fail2ban |
| `custom-jails/` | Thư mục chứa các jail tùy chỉnh |

## Các lệnh thường dùng

```bash
# Xem trạng thái
sudo fail2ban-client status

# Xem jail cụ thể
sudo fail2ban-client status sshd

# Unban IP
sudo fail2ban-client set sshd unbanip <IP>

# Reload cấu hình
sudo fail2ban-client reload

# Xem log
sudo tail -f /var/log/fail2ban.log
```

## Áp dụng cấu hình mới

```bash
# Copy file cấu hình vào /etc/fail2ban/
sudo cp /home/fail2ban/custom-jails/*.local /etc/fail2ban/jail.d/

# Reload fail2ban
sudo fail2ban-client reload
```

## Cấu hình hiện tại

- **SSH jail**: Bật, ban 24h sau 3 lần thử sai
- **Ban action**: UFW firewall
- **Find time**: 10 phút
