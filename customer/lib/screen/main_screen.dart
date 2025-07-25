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
  final int initialIndex;
  final String? selectedCategoryName; // ✅ thêm dòng này

  const MainScreen({
    Key? key,
    this.initialIndex = 0,
    this.selectedCategoryName,
  }) : super(key: key);


  @override
  State<MainScreen> createState() => _MainScreenState();
}
class _MainScreenState extends State<MainScreen> {
  int _currentIndex = 0;
  int? _pendingTabIndex;

  @override
  void initState() {
    super.initState();
    _currentIndex = widget.initialIndex; // ✅ lấy index ban đầu
  }
  @override
  Widget build(BuildContext context) {
    Widget displayedScreen;

    switch (_currentIndex) {
      case 0:
        displayedScreen = const HomeScreen();
        break;
      case 1:
        displayedScreen = CategoryScreen(selectedCategoryName: widget.selectedCategoryName);
        break;
      case 2:
          displayedScreen = const CartScreen();
        break;
      case 3:
          displayedScreen = const OrderHistoryScreen(); // ✅ Đổi sang màn lịch sử
        break;
      case 4:
        displayedScreen = const MoreScreen();
        break;
      default:
        displayedScreen = const HomeScreen();
    }

    return Scaffold(
      body: displayedScreen,
      bottomNavigationBar: BottomNavBar(
        currentIndex: _currentIndex,
        onTap: (index) {
          if ((index == 2 || index == 3) && currentUser == null) {
            _pendingTabIndex = index;
            Navigator.pushNamed(context, '/login').then((_) {
              if (currentUser != null && _pendingTabIndex != null) {
                setState(() {
                  _currentIndex = _pendingTabIndex!;
                  _pendingTabIndex = null;
                });
              }
            });
            return;
          }
          // ✅ Các tab khác thì đổi bình thường
          setState(() {
            _currentIndex = index;
          });
        },
      ),
    );
  }
}