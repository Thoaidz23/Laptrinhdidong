import 'package:flutter/material.dart';
import '../model/cart_item.dart';
import '../model/product.dart';
import '../services/api_service.dart';

class CartProvider with ChangeNotifier {
  final int userId;

  CartProvider(this.userId);

  List<CartItem> _items = [];

  List<CartItem> get items => _items;

  double get totalPrice => _items.fold(0, (sum, item) => sum + item.price * item.quantity);

  Future<void> fetchCartFromAPI() async {
    _items = await ApiService.getCart(userId);
    notifyListeners();
  }

  Future<void> addToCart(Product product) async {
    await ApiService.addToCart(userId, product.id, 1, product.price); // ✅ truyền thêm price
    await fetchCartFromAPI();
  }

  Future<void> updateQuantity(int productId, int quantity) async {
    await ApiService.updateCart(userId, productId, quantity);
    await fetchCartFromAPI();
  }

  Future<void> removeFromCart(int productId) async {
    await ApiService.deleteCartItem(userId, productId);
    await fetchCartFromAPI();
  }

  Future<void> checkoutCart() async {
    await ApiService.checkoutCart(userId);
    _items.clear();
    notifyListeners();
  }
}
