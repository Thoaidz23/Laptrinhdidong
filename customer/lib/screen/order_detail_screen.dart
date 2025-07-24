import 'package:flutter/material.dart';
import '../model/order.dart';

class OrderDetailScreen extends StatelessWidget {
  final Order order;

  const OrderDetailScreen({super.key, required this.order});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Chi tiết đơn hàng"),
      ),
      body: const Center(
        child: Text("Đang phát triển..."),
      ),
    );
  }
}