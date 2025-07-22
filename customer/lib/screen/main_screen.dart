import 'package:flutter/material.dart';
import '../Widget/MenuBar.dart';
import 'home_screen.dart';
import 'cart_screen.dart';
import 'login_screen.dart';
import 'category_screen.dart';
import 'more_screen.dart';
import 'order_history_screen.dart'; // ✅ Thêm import màn lịch sử
import '../model/user.dart';

class MainScreen extends StatefulWidget {
  const MainScreen({Key? key}) : super(key: key);

  @override
  State<MainScreen> createState() => _MainScreenState();
}

class _MainScreenState extends State<MainScreen> {
  int _currentIndex = 0;

  @override
  Widget build(BuildContext context) {
    Widget displayedScreen;

    switch (_currentIndex) {
      case 0:
        displayedScreen = const HomeScreen();
        break;
      case 1:

        displayedScreen = const CategoryScreen();

        break;
      case 2:
        if (currentUser == null) {
          Future.microtask(() {
            Navigator.pushNamed(context, '/login').then((_) => setState(() {}));
          });
          displayedScreen = const SizedBox.shrink();
        } else {

          displayedScreen = const CartScreen();

        }
        break;
      case 3:
        if (currentUser == null) {
          Future.microtask(() {
            Navigator.pushNamed(context, '/login').then((_) => setState(() {}));
          });
          displayedScreen = const SizedBox.shrink();
        } else {

          displayedScreen = const OrderHistoryScreen(); // ✅ Đổi sang màn lịch sử

        }
        break;
      case 4:
        if (currentUser == null) {
          Future.microtask(() {
            Navigator.pushNamed(context, '/login').then((_) => setState(() {}));
          });
          displayedScreen = const SizedBox.shrink();
        } else {
          displayedScreen = const MoreScreen();
        }
        break;
      default:
        displayedScreen = const HomeScreen();
    }

    return Scaffold(
      body: displayedScreen,
      bottomNavigationBar: BottomNavBar(
        currentIndex: _currentIndex,
        onTap: (index) {
          setState(() {
            _currentIndex = index;
          });
        },
      ),
    );
  }
}