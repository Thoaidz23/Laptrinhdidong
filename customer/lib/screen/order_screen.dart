
// screens/order_screen.dart
import 'package:flutter/material.dart';

class OrderScreen extends StatelessWidget {
  const OrderScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Đơn hàng của tôi')),
      body: ListView(
        children: const [
          ListTile(title: Text('Đơn hàng #001'), subtitle: Text('Ngày: 01/01/2025 - 300,000 VND')),
          ListTile(title: Text('Đơn hàng #002'), subtitle: Text('Ngày: 03/01/2025 - 150,000 VND')),
        ],
      ),
    );
  }
}
