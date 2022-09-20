# Check Limit Login Attempts REST API
Plugins สำหรับดึงข้อมูลจากฐานข้อมูลในเว็บไซต์ที่ Plugins: Limit Login Attempts Reloaded เก็บไว้ในฐานข้อมูลตาราง wp-options ในรูปแบบ serialize Array ออกมาเป็น REST API

## ความสามารถของ Plugins
* ตรวจสอบการติดตั้ง Plugins: Limit Login Attempts Reloaded
* สร้าง REST API เส้นที่ 1 คือ /limit-login-retries-stats/
* สร้าง REST API เส้นที่ 2 คือ /limit-login-logged/

> ตัวอย่าง API เมื่อติดตั้งเสร็จ
* https://nann.me/wp-json/nannme/v1/limit-login-retries-stats/
* https://nann.me/wp-json/nannme/v1/limit-login-logged/

## Plugins ช่วยแก้ไขปัญหาอย่างไร?
หากคุณมีเว็บไซต์ที่ต้องดูแลจำนวน ๆ มาก ๆ เช่น 10 - 20 เว็บไซต์ และ ทั้งหมดเป็น WordPress คุณจะรู้ได้อย่างไรว่าตอนนี้เว็บไซต์ทั้งหมดที่ดูแล ตอนนี้มีเว็บไซต์ไหนบ้างที่ถูกโจมตี และ ถูกโจมตีมากน้อยแค่ไหน จะได้วางแผนถูกว่าควรถึงเวลาที่ต้องนำเว็บไซต์ไปหลบหลัง Cloudflare WAF แล้วหรือยัง?

![Demo Use](https://img001.prntscr.com/file/img001/ZtBb6dyWToaNXwlUKNhWYg.jpeg)
