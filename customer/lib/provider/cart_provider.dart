import 'package:flutter/material.dart';
import '../model/cart_item.dart';
import '../model/product.dart';
import '../services/api_service.dart';

class CartProvider with ChangeNotifier {
  int _userId;

  CartProvider(this._userId);

  void updateUserId(int userId) {
    _userId = userId;
    notifyListeners();
  }

  List<CartItem> _items = [];

  List<CartItem> get items => _items;

  double get totalPrice => _items.fold(0, (sum, item) => sum + item.price * item.quantity);

  Future<void> fetchCartFromAPI() async {
    _items = await ApiService.getCart(_userId);
    notifyListeners();
  }

  Future<void> addToCart(Product product) async {
    await ApiService.addToCart(_userId, product.id, 1, product.price);
    await fetchCartFromAPI();
  }

  Future<void> updateQuantity(int productId, int quantity) async {
    await ApiService.updateCart(_userId, productId, quantity);
    await fetchCartFromAPI();
  }

  Future<void> removeFromCart(int productId) async {
    await ApiService.deleteCartItem(_userId, productId);
    await fetchCartFromAPI();
  }

  Future<void> checkoutCart() async {
    await ApiService.checkoutCart(_userId);
    _items.clear();
    notifyListeners();
  }
}

