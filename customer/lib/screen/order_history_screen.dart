import 'package:flutter/material.dart';
import '../model/order.dart';
import '../services/api_service.dart';
import '../Widget/Header.dart';
import '../model/user.dart';
import 'order_detail_screen.dart';

import 'package:intl/intl.dart';
final currencyFormatter = NumberFormat("#,###", "vi_VN");

class OrderHistoryScreen extends StatefulWidget {
  const OrderHistoryScreen({super.key});

  @override
  State<OrderHistoryScreen> createState() => _OrderHistoryScreenState();
}

class _OrderHistoryScreenState extends State<OrderHistoryScreen> {
  final List<String> statuses = const [
    'Tất cả',
    'Chờ xác nhận',
    'Đã xác nhận',
    'Đang vận chuyển',
    'Đã giao',
    'Đã hủy',
  ];

  String selectedStatus = 'Tất cả';
  List<Order> orders = [];
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    loadOrders();
  }

  Future<void> loadOrders() async {
    try {
      int userId = currentUser?.id ?? 0;
      List<Order> data = await ApiService.fetchOrders(userId);
      setState(() {
        orders = data;
        isLoading = false;
      });
    } catch (e) {
      print("Lỗi khi tải đơn hàng: $e");
      setState(() {
        isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F5F5),
      body: Column(
        children: [
          const Header(),

          // Tiêu đề
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
            decoration: const BoxDecoration(
              color: Colors.orange,
              boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 4)],
            ),
            child: Row(
              children: [
                const SizedBox(height: 20, width: 50,),
                const Expanded(
                  child: Center(
                    child: Text(
                      'Lịch sử đơn hàng',
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

          // Thanh chọn trạng thái
          Container(
            height: 42,
            margin: const EdgeInsets.only(top: 8),
            child: SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: Row(
                children: statuses.map((status) {
                  final isActive = selectedStatus == status;
                  return GestureDetector(
                    onTap: () {
                      setState(() {
                        selectedStatus = status;
                      });
                    },
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 16),
                      alignment: Alignment.center,
                      decoration: BoxDecoration(
                        border: Border(
                          bottom: BorderSide(
                            color: isActive ? Colors.deepOrange : Colors.transparent,
                            width: 2,
                          ),
                        ),
                      ),
                      height: double.infinity,
                      child: Text(
                        status,
                        style: TextStyle(
                          color: isActive ? Colors.red : Colors.black,
                          fontSize: 13,
                          fontWeight: isActive ? FontWeight.bold : FontWeight.normal,
                        ),
                      ),
                    ),
                  );
                }).toList(),
              ),
            ),
          ),

          const SizedBox(height: 8),

          // Danh sách đơn hàng
          Expanded(
            child: isLoading
                ? const Center(child: CircularProgressIndicator())
                : orders.isEmpty
                ? const Center(child: Text("Không có đơn hàng nào"))
                : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: orders.length,
              itemBuilder: (context, index) {
                final order = orders[index];

                if (selectedStatus != 'Tất cả' &&
                    order.status != selectedStatus) {
                  return const SizedBox.shrink();
                }

                return GestureDetector(
                  onTap: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => OrderDetailScreen(order: order),
                      ),
                    );
                  },
                  child: Container(
                    margin: const EdgeInsets.only(bottom: 12),
                    padding: const EdgeInsets.all(12),
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
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(8),
                          child: Image.network(
                            order.image,
                            width: 60,
                            height: 60,
                            fit: BoxFit.cover,
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text("Mã đơn: ${order.id}",
                                  style: const TextStyle(
                                      fontWeight: FontWeight.bold,
                                      fontSize: 16)),
                              const SizedBox(height: 4),
                              Text(
                                  "Ngày mua: ${order.date} lúc ${order.time}"),
                              Text(
                                "Tổng tiền: ${NumberFormat('#,###', 'vi_VN').format(order.total)} đ",
                                style: const TextStyle(color: Colors.green),
                              ),

                              Text(
                                  "Thanh toán: ${order.isPaid ? "Đã thanh toán" : "Chưa thanh toán"}",
                                  style: TextStyle(
                                      color: order.isPaid
                                          ? Colors.green
                                          : Colors.red)),
                              Text("Trạng thái: ${order.status}"),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
        ],
      ),

    );
  }
}
