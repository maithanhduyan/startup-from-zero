# UFW
Universal Firewall (UFW) là một giao diện đơn giản để quản lý iptables, giúp người dùng dễ dàng cấu hình tường lửa trên hệ thống Linux.
> ## Mục lục
> - [Tổng quan](#tổng-quan)
> - [Cài đặt UFW](#cài-đặt-ufw)
> - [Cấu hình cơ bản](#cấu-hình-cơ-bản)
> - [Quản lý UFW](#quản-lý-ufw)
> - [Thiết lập nâng cao](#thiết-lập-nâng-cao)
> - [Tài liệu tham khảo](#tài-liệu-tham-khảo
## Tổng quan
UFW (Uncomplicated Firewall) là một công cụ quản lý tường lửa dành cho hệ điều hành Linux, được thiết kế để đơn giản hóa việc cấu hình iptables. UFW cung cấp một giao diện dòng lệnh dễ sử dụng, giúp người dùng nhanh chóng thiết lập các quy tắc tường lửa để bảo vệ hệ thống khỏi các truy cập không mong muốn
## Cài đặt UFW
Trên hầu hết các bản phân phối Linux dựa trên Debian (như Ubuntu), bạn có thể cài đặt UFW bằng lệnh sau:
```bash
sudo apt update
sudo apt install ufw
```
## Cấu hình cơ bản
1. **Kiểm tra trạng thái UFW**:
   ```bash
   sudo ufw status verbose
   ```
2. **Kích hoạt UFW**:
   ```bash
   sudo ufw enable
   ```
3. **Vô hiệu hóa UFW**:
   ```bash
   sudo ufw disable
  ```
4. **Cho phép kết nối SSH (rất quan trọng để tránh bị khóa ngoài)**:
    ```bash
    sudo ufw allow ssh
    ```
5. **Cho phép một cổng cụ thể (ví dụ: HTTP trên cổng 80)**:
   ```bash
   sudo ufw allow 80/tcp
   ```
6. **Chặn một cổng cụ thể (ví dụ: FTP trên cổng 21)**:
   ```bash
   sudo ufw deny 21/tcp
   ```
## Quản lý UFW
1. **Xem các quy tắc hiện tại**:
   ```bash
   sudo ufw status numbered
   ```
2. **Xóa một quy tắc cụ thể (ví dụ: quy tắc số 2)**:
   ```bash
   sudo ufw delete 2
   ```
3. **Đặt chính sách mặc định (ví dụ: chặn tất cả các kết nối đến)**:
   ```bash
   sudo ufw default deny incoming
   sudo ufw default allow outgoing
   ```
## Thiết lập nâng cao
1. **Cho phép một dải IP cụ thể**:
   ```bash
   sudo ufw allow from
  ```

2. **Chặn một dải IP cụ thể**:
  ```bash
    sudo ufw deny from <IP_ADDRESS>/<SUBNET_MASK>
  ```
3. **Cho phép một dịch vụ cụ thể (ví dụ: Samba)**:
   ```bash
   sudo ufw allow Samba
   ```
4. **Ghi log các hoạt động của UFW**:
   ```bash
   sudo ufw logging on
   ```
## Tài liệu tham khảo
- [UFW Official Documentation](https://help.ubuntu.com/community/UFW)
- [Iptables vs UFW](https://wiki.ubuntu.com/UncomplicatedFirewall)
- [UFW Cheat Sheet](https://www.digitalocean.com/community/tutorials/ufw-essentials-common-firewall-rules-and-commands)
# Linux Server Protection Setup