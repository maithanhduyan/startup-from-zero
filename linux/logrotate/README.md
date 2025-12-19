# Logrotate Configuration Backup

## Thư mục này chứa:

| File/Folder | Mô tả |
|-------------|-------|
| `logrotate.conf.backup` | Backup cấu hình gốc |
| `custom-configs/` | Thư mục chứa cấu hình tùy chỉnh |

## Cấu hình mặc định

- **Rotation**: Hàng tuần (weekly)
- **Giữ lại**: 4 bản backup
- **Nén**: Chưa bật

## Các lệnh thường dùng

```bash
# Kiểm tra cấu hình (dry-run)
sudo logrotate -d /etc/logrotate.conf

# Chạy logrotate thủ công
sudo logrotate -f /etc/logrotate.conf

# Chạy một file cấu hình cụ thể
sudo logrotate -f /etc/logrotate.d/<service>

# Xem trạng thái
cat /var/lib/logrotate/status
```

## Áp dụng cấu hình mới

```bash
# Copy file cấu hình vào /etc/logrotate.d/
sudo cp /home/logrotate/custom-configs/<config-file> /etc/logrotate.d/

# Kiểm tra cấu hình
sudo logrotate -d /etc/logrotate.conf
```

## Ví dụ cấu hình custom

```
/var/log/myapp/*.log {
    daily
    rotate 7
    compress
    delaycompress
    missingok
    notifempty
    create 0640 www-data www-data
    sharedscripts
    postrotate
        systemctl reload myapp > /dev/null 2>&1 || true
    endscript
}
```

## Các options phổ biến

| Option | Mô tả |
|--------|-------|
| `daily/weekly/monthly` | Tần suất rotate |
| `rotate N` | Giữ lại N bản backup |
| `compress` | Nén file log cũ |
| `delaycompress` | Nén sau 1 lần rotate |
| `missingok` | Không báo lỗi nếu file không tồn tại |
| `notifempty` | Không rotate nếu file rỗng |
| `create mode owner group` | Tạo file mới với permission |
| `copytruncate` | Copy và truncate (cho app không hỗ trợ reload) |
