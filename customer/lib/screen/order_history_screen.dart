import 'package:flutter/material.dart';
import '../Widget/Header.dart';
import '../model/order.dart';
import '../services/api_service.dart';
import '../model/user.dart';

class OrderHistoryScreen extends StatefulWidget {
  const OrderHistoryScreen({super.key});

  @override
  State<OrderHistoryScreen> createState() => _OrderHistoryScreenState();
}

class _OrderHistoryScreenState extends State<OrderHistoryScreen> {
  final List<String> statuses = const [
    'Tất cả',
    'Đã giao',
    'Đang vận chuyển',
    'Đã hủy',
    'Đã xác nhận',
    'Chờ xác nhận',
  ];

  List<Order> orders = [];
  String selectedStatus = 'Tất cả';
  bool isLoading = true;

  User? currentUser; // bạn cần gán currentUser từ hệ thống của bạn

  @override
  void initState() {
    super.initState();
    // Gán currentUser từ nơi lưu trữ trong app bạn
    // currentUser = AuthService.currentUser; // ví dụ
    fetchOrders();
  }

  Future<void> fetchOrders() async {
    if (currentUser == null) {
      print('❌ Không có người dùng hiện tại');
      setState(() => isLoading = false);
      return;
    }

    try {
      List<Order> fetchedOrders =
      await ApiService.fetchOrdersFromApi(currentUser!.id);
      setState(() {
        orders = fetchedOrders;
        isLoading = false;
      });
    } catch (e) {
      print('❌ Lỗi khi tải đơn hàng: $e');
      setState(() => isLoading = false);
    }
  }

  String _statusToText(int status) {
    switch (status) {
      case 1:
        return 'Đã giao';
      case 2:
        return 'Đang vận chuyển';
      case 3:
        return 'Đã hủy';
      case 4:
        return 'Đã xác nhận';
      default:
        return 'Chờ xác nhận';
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
                IconButton(
                  icon: const Icon(Icons.arrow_back, color: Colors.white),
                  onPressed: () => Navigator.pop(context),
                ),
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

          // Lọc trạng thái
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
                        color: Colors.transparent,
                        border: Border(
                          bottom: BorderSide(
                            color: isActive
                                ? Colors.deepOrange
                                : Colors.transparent,
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
                          fontWeight:
                          isActive ? FontWeight.bold : FontWeight.normal,
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
                final statusText = _statusToText(order.status);

                if (selectedStatus != 'Tất cả' &&
                    selectedStatus != statusText) {
                  return const SizedBox.shrink();
                }

                return Container(
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
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text("Mã đơn: ${order.id}",
                          style: const TextStyle(
                              fontWeight: FontWeight.bold,
                              fontSize: 16)),
                      const SizedBox(height: 4),
                      Text("Ngày mua: ${order.orderDate}"),
                      Text("Tổng tiền: ${order.totalPrice}đ",
                          style: const TextStyle(color: Colors.green)),
                      Text("Trạng thái: $statusText"),
                    ],
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
