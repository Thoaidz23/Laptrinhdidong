import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../screen/main_screen.dart';
class CategorySection extends StatefulWidget {
  const CategorySection({super.key});

  @override
  State<CategorySection> createState() => _CategorySectionState();
}
final Map<int, String> categoryNames = {
  1: 'Snack',
  2: 'Bánh',
  3: 'Kẹo',
  4: 'Thức uống đóng hộp',
  5: 'Đồ ăn đóng hộp',
  6: 'Đồ ăn liền',
};
int? getCategoryIdFromName(String name) {
  final input = name.trim().toLowerCase(); // 👈 chuẩn hóa chuỗi
  return categoryNames.entries
      .firstWhere(
        (entry) => entry.value.toLowerCase() == input,
    orElse: () => const MapEntry(0, ''),
  )
      .key;
}


class _CategorySectionState extends State<CategorySection> {
  List<dynamic> categories = [];

  @override
  void initState() {
    super.initState();
    fetchCategories();
  }

  Future<void> fetchCategories() async {
    final response = await http.get(Uri.parse('http://10.0.2.2/ttsfood/api/get_categories.php'));

    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      setState(() {
        categories = data;
      });
    } else {
      print('Lỗi tải danh mục: ${response.statusCode}');
    }
  }

  IconData getIconData(String iconName) {
    switch (iconName) {
      case 'fastfood':
        return Icons.fastfood;
      case 'cake':
        return Icons.cake;
      case 'candy':
        return Icons.coronavirus_outlined;
      case 'emoji_food_beverage':
        return Icons.emoji_food_beverage;
      case 'lunch_dining':
        return Icons.lunch_dining;
      case 'ramen_dining':
        return Icons.ramen_dining;
      default:
        return Icons.category;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
        padding: const EdgeInsets.symmetric(horizontal: 16.0),
    child: Column(
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
    const Text(
    'DANH MỤC SẢN PHẨM',
    style: TextStyle(
    fontSize: 20,
    fontWeight: FontWeight.bold,
    color: Colors.deepOrange,
    ),
    ),
    const SizedBox(height: 10),
    SizedBox(
    height: 90,
    child: categories.isEmpty
    ? const Center(child: CircularProgressIndicator())
        : ListView.builder(
    scrollDirection: Axis.horizontal,
    itemCount: categories.length,
    itemBuilder: (context, index) {
    final category = categories[index];
    final icon = getIconData(category['icon_name'] ?? '');

    return Container(
    width: 80,
    margin: const EdgeInsets.only(right: 12),
    decoration: BoxDecoration(
    color: Colors.orange.shade50,
    borderRadius: BorderRadius.circular(12),
    ),
    child: InkWell(
      onTap: () {
        int id = getCategoryIdFromName(category['name']) ?? 0;

        // Nếu không tìm thấy ID, chuyển sang Đồ ăn liền (id = 6)
        if (id == 0) {
          id = 6;
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Không tìm thấy danh mục, chuyển sang Đồ ăn liền')),
          );
        }

        Navigator.pushReplacement(
          context,
          MaterialPageRoute(
            builder: (_) => MainScreen(
              initialIndex: 1,
              selectedCategoryName: category['name'], // ✅ truyền tên danh mục
            ),
          ),
        );


      },


      child: Column(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Icon(icon, color: Colors.orange, size: 30),
        const SizedBox(height: 6),
        Text(
          category['name'],
          textAlign: TextAlign.center,
          style: const TextStyle(fontSize: 13),
        ),
      ],
    ),
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