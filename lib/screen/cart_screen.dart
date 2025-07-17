import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../provider/cart_provider.dart';

class CartScreen extends StatelessWidget {
  const CartScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final cart = Provider.of<CartProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Giỏ hàng'),
      ),
      body: cart.items.isEmpty
          ? const Center(child: Text('Giỏ hàng trống'))
          : Column(
        children: [
          Expanded(
            child: ListView.builder(
              itemCount: cart.items.length,
              itemBuilder: (context, index) {
                final item = cart.items[index];
                return ListTile(
                  leading: Image.network(item.product.imageUrl, width: 60, height: 60, fit: BoxFit.cover),
                  title: Text(item.product.name),
                  subtitle: Text('Số lượng: ${item.quantity}'),
                  trailing: Text('${(item.product.price * item.quantity).toStringAsFixed(0)} đ'),
                  onLongPress: () => cart.removeFromCart(item.product),
                );
              },
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text('Tổng:', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                    Text('${cart.totalPrice.toStringAsFixed(0)} đ', style: const TextStyle(fontSize: 20, color: Colors.orange)),
                  ],
                ),
                const SizedBox(height: 10),
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: () {
                      // TODO: gọi API đặt hàng
                      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Đã đặt hàng (giả lập)')));
                      cart.clearCart();
                      Navigator.pop(context);
                    },
                    style: ElevatedButton.styleFrom(backgroundColor: Colors.orange),
                    child: const Text('Đặt hàng'),
                  ),
                ),
              ],
            ),
          )
        ],
      ),
    );
  }
}
