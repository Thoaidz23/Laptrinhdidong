import 'package:flutter/material.dart';
import '../Widget/Header.dart';

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

  final List<Map<String, dynamic>> orders = const [
    {
      'id': 'DH001',
      'status': 'Đã giao',
      'date': '20/07/2025',
      'time': '14:30',
      'total': 90000,
      'image': 'https://th.bing.com/th/id/OIP.IVbCUBe9BnqBgT36EG3H5QHaHa?w=186&h=186',
      'item': 'Bánh mì thịt, Cơm gà xối mỡ',
    },
    {
      'id': 'DH002',
      'status': 'Đang vận chuyển',
      'date': '21/07/2025',
      'time': '10:15',
      'total': 75000,
      'image': 'https://th.bing.com/th/id/OIP.IVbCUBe9BnqBgT36EG3H5QHaHa?w=186&h=186',
      'item': 'Trà sữa trân châu',
    },
    {
      'id': 'DH003',
      'status': 'Đã hủy',
      'date': '19/07/2025',
      'time': '08:00',
      'total': 150000,
      'image': 'https://th.bing.com/th/id/OIP.IVbCUBe9BnqBgT36EG3H5QHaHa?w=186&h=186',
      'item': 'Cơm gà, Trà sữa, Bánh mì',
    },
  ];

  String selectedStatus = 'Tất cả';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        backgroundColor: const Color(0xFFF5F5F5),
    body: Column(
    children: [
    const Header(),

    // Tiêu đề với nút quay lại
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
    color: Colors.transparent,
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
    child: ListView.builder(
    padding: const EdgeInsets.all(16),
    itemCount: orders.length,
    itemBuilder: (context, index) {
    final order = orders[index];

    if (selectedStatus != 'Tất cả' &&
    order['status'] != selectedStatus) {
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
    child: Row(
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
    ClipRRect(
    borderRadius: BorderRadius.circular(8),
    child: Image.network(
    order['image'],
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
        Text("Mã đơn: ${order['id']}",
            style: const TextStyle(
                fontWeight: FontWeight.bold, fontSize: 16)),
        const SizedBox(height: 4),
        Text("Sản phẩm: ${order['item']}"),
        Text("Ngày mua: ${order['date']} lúc ${order['time']}"),
        Text("Tổng tiền: ${order['total']}đ",
            style: const TextStyle(color: Colors.green)),
        Text("Trạng thái: ${order['status']}"),
      ],
    ),
    ),
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