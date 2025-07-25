import 'package:flutter/material.dart';
import '../model/order.dart';
import '../widget/header.dart';
import '../widget/MenuBar.dart';

class OrderDetailScreen extends StatelessWidget {
  final Order order;

  const OrderDetailScreen({super.key, required this.order});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F5F5),


      body: Column(
        children: [
          const Header(),

          // Thanh tiêu đề
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
            decoration: const BoxDecoration(
              color: Colors.orange,
              boxShadow: [
                BoxShadow(color: Colors.black12, blurRadius: 4),
              ],
            ),
            child: Row(
              children: [
                IconButton(
                  icon: const Icon(Icons.arrow_back, color: Colors.white),
                  onPressed: () => Navigator.pop(context),
                ),
                const Expanded(
                  child: Center(
                    child: Text(
                      'Chi tiết đơn hàng',
                      style: TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                        color: Colors.white,
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 48),
              ],
            ),
          ),

          Expanded(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  // === DIV 1: Thông tin đơn hàng + sản phẩm ===
                  Container(
                    width: double.infinity,
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(12),
                      boxShadow: const [
                        BoxShadow(
                          color: Colors.black12,
                          blurRadius: 6,
                          offset: Offset(0, 2),
                        ),
                      ],
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text("Mã đơn hàng: ${order.id}",
                            style: const TextStyle(
                                fontSize: 16, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 8),
                        Text("Ngày đặt: ${order.date}"),
                        Text("Giờ: ${order.time}"),
                        Text("Trạng thái: ${order.status}",
                            style: const TextStyle(color: Colors.orange)),

                        const SizedBox(height: 20),
                        const Text("Sản phẩm đã đặt:",
                            style: TextStyle(fontWeight: FontWeight.bold)),
                        const SizedBox(height: 12),

                        _buildDemoItem(
                          image: order.image,
                          name: order.item,
                          quantity: 2,
                          price: 20000,
                        ),
                        const Divider(thickness: 0.5, color: Colors.grey),
                        _buildDemoItem(
                          image: order.image,
                          name: "Bánh tráng cuộn tôm khô",
                          quantity: 1,
                          price: 25000,
                        ),
                        const Divider(thickness: 0.5, color: Colors.grey),
                        _buildDemoItem(
                          image: order.image,
                          name: "Trà sữa trân châu đường đen",
                          quantity: 3,
                          price: 30000,
                        ),

                        const SizedBox(height: 24),
                        Align(
                          alignment: Alignment.centerRight,
                          child: Text(
                            "Tổng tiền: ${order.total}đ",
                            style: const TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                              color: Colors.green,
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),

                  // === DIV 2: Thông tin khách hàng ===
                  Container(
                    margin: const EdgeInsets.only(top: 10), // Cách div 1 10px
                    width: double.infinity,
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(12),
                      boxShadow: const [
                        BoxShadow(
                          color: Colors.black12,
                          blurRadius: 6,
                          offset: Offset(0, 2),
                        ),
                      ],
                    ),
                    child: const Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          "Thông tin khách hàng:",
                          style: TextStyle(
                            fontSize: 16,
                            fontWeight: FontWeight.bold,
                            color: Colors.black87,
                          ),
                        ),
                        SizedBox(height: 8),
                        Text("👤 Tên: Nguyễn Văn A"),
                        Text("📧 Email: nguyenvana@gmail.com"),
                        Text("📞 SĐT: 0901234567"),
                        Text("📍 Địa chỉ: 123 Lê Lợi, Quận 1, TP.HCM"),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildDemoItem({
    required String image,
    required String name,
    required int quantity,
    required int price,
  }) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(8),
            child: Image.network(
              image,
              width: 80,
              height: 80,
              fit: BoxFit.cover,
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(name,
                    style: const TextStyle(
                        fontSize: 14, fontWeight: FontWeight.w500)),
                const SizedBox(height: 4),
                Text("Số lượng: $quantity",
                    style: const TextStyle(color: Colors.black54)),
                Text("Giá: ${price}đ",
                    style: const TextStyle(color: Colors.black54)),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
