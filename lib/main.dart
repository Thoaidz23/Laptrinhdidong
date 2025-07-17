import 'package:flutter/material.dart';
import 'screen/home_screen.dart';
import 'screen/product_detail_screen.dart';
import 'screen/cart_screen.dart';
import 'screen/login_screen.dart';
import 'screen/register_screen.dart';
import 'screen/order_screen.dart';
import 'services/api_service.dart';
import 'model/user.dart';
import 'model/product.dart';
import 'package:provider/provider.dart';
import 'provider/cart_provider.dart';

User? currentUser;

void main() {
  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => CartProvider()),
      ],
      child: const MyApp(),
    ),
  );
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'FastFood App',
      theme: ThemeData(primarySwatch: Colors.orange),
      initialRoute: '/',
      routes: {
        '/': (context) => const HomeScreen(),
        '/cart': (context) => const CartScreen(),
        '/login': (context) => const LoginScreen(),
        '/register': (context) => const RegisterScreen(),
        '/orders': (context) => const OrderScreen(),
      },
    );
  }
}
