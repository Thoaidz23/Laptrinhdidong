import 'package:flutter/material.dart';
import '../model/order.dart';
import '../widget/header.dart';
import '../services/api_service.dart';
import '../model/order_item.dart';

class OrderDetailScreen extends StatefulWidget {
  final Order order;

  const OrderDetailScreen({super.key, required this.order});

  @override
  State<OrderDetailScreen> createState() => _OrderDetailScreenState();
}

class _OrderDetailScreenState extends State<OrderDetailScreen> {
  List<OrderItem> orderItems = [];
  late Order currentOrder;
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    currentOrder = widget.order; // ✅ khởi tạo trước
    loadOrderDetail();
  }

  Future<void> loadOrderDetail() async {
    final data = await ApiService().fetchOrderDetail(widget.order.id);
    if (data != null) {
      final itemsData = data['items'] as List;
      final orderData = data['order']; // ✅ để lấy các thông tin như name, phone, address

      setState(() {
        currentOrder = Order.fromJson(orderData); // ✅ gán lại currentOrder
        orderItems = itemsData.map((item) => OrderItem.fromJson(item)).toList();
        isLoading = false;
      });
    } else {
      setState(() {
        isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Không thể tải chi tiết đơn hàng')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F5F5),
      body: Column(
        children: [
          const Header(),
          Container(
            width: double.infinity,
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
            decoration: const BoxDecoration(
              color: Colors.orange,
              boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 4)],
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
                      style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.white),
                    ),
                  ),
                ),
                const SizedBox(width: 48),
              ],
            ),
          ),
          Expanded(
            child: isLoading
                ? const Center(child: CircularProgressIndicator())
                : SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  _buildOrderInfo(currentOrder),
                  const SizedBox(height: 10),
                  _buildCustomerInfo(currentOrder),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildOrderInfo(Order order) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: const [BoxShadow(color: Colors.black12, blurRadius: 6, offset: Offset(0, 2))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text("Mã đơn hàng: ${order.id}", style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          Text("Ngày đặt: ${order.date}"),
          Text("Giờ: ${order.time}"),
          Text("Trạng thái: ${order.status}", style: const TextStyle(color: Colors.orange)),
          const SizedBox(height: 20),
          const Text("Sản phẩm đã đặt:", style: TextStyle(fontWeight: FontWeight.bold)),
          const SizedBox(height: 12),
          ...orderItems.map((item) => Column(
            children: [
              _buildOrderItem(
                image: item.image,
                name: item.name,
                quantity: item.quantity,
                price: item.price,
              ),
              const Divider(thickness: 0.5, color: Colors.grey),
            ],
          )),
          const SizedBox(height: 24),
          Align(
            alignment: Alignment.centerRight,
            child: Text(
              "Tổng tiền: ${order.total}đ",
              style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.green),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCustomerInfo(Order order) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: const [BoxShadow(color: Colors.black12, blurRadius: 6, offset: Offset(0, 2))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            "Thông tin khách hàng:",
            style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.black87),
          ),
          const SizedBox(height: 8),
          Text("👤 Tên: ${order.name}"),
          Text("📞 SĐT: ${order.phone}"),
          Text("📍 Địa chỉ: ${order.address}"),
          // ❌ Bỏ dòng dưới nếu không có email trong Order model
          // Text("📧 Email: ${order.email}"),
        ],
      ),
    );
  }

  Widget _buildOrderItem({
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
                Text(name, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w500)),
                const SizedBox(height: 4),
                Text("Số lượng: $quantity", style: const TextStyle(color: Colors.black54)),
                Text("Giá: ${price}đ", style: const TextStyle(color: Colors.black54)),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
