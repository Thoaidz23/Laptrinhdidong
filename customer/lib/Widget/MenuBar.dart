// Widget/bottom_nav_bar.dart
import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';

class BottomNavBar extends StatelessWidget {
  final int currentIndex;
  final Function(int) onTap;

  const BottomNavBar({
    super.key,
    required this.currentIndex,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return BottomNavigationBar(
      currentIndex: currentIndex,
      onTap: onTap,
      selectedItemColor: Colors.black,
      unselectedItemColor: Colors.grey,
      type: BottomNavigationBarType.fixed,
      items: const [
        BottomNavigationBarItem(
          icon: Icon(Icons.home_outlined),
          label: "Nhà",
        ),
        BottomNavigationBarItem(
          icon: Icon(FontAwesomeIcons.tags),
          label: "Danh mục",
        ),
        BottomNavigationBarItem(
          icon: Icon(Icons.shopping_bag_outlined),
          label: "Giỏ hàng",
        ),

        BottomNavigationBarItem(
          icon: Icon(Icons.restaurant_menu_outlined),
          label: "Đơn Hàng",
        ),
        BottomNavigationBarItem(
          icon: Icon(Icons.menu),
          label: "Thêm",
        ),
      ],
    );
  }
}