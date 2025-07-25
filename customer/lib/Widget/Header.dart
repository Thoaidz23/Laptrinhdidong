import 'package:flutter/material.dart';
import '../screen/account_screen.dart'; // Import đúng đường dẫn đến file AccountPage
import '../Widget/Header.dart';
class Header extends StatelessWidget {
  const Header({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.fromLTRB(16, 40, 16, 14),
      decoration: BoxDecoration(
        border: Border(
          bottom: BorderSide(color: Colors.grey.shade300),
        ),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          const Text(
            'TTSFood',
            style: TextStyle(
              fontSize: 24,
              fontWeight: FontWeight.bold,
              color: Colors.red,
            ),
          ),
          Row(
            children: [
              const SizedBox(width: 30),
              GestureDetector(
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (_) => const AccountPage()),
                  );
                },
                child: const Icon(Icons.account_circle, size: 38),
              ),
            ],
          )
        ],
      ),
    );
  }
}