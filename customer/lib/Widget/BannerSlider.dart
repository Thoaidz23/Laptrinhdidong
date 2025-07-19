// widget/banner_slider.dart
import 'package:flutter/material.dart';

class BannerSlider extends StatefulWidget {
  const BannerSlider({super.key});

  @override
  State<BannerSlider> createState() => _BannerSliderState();
}

class _BannerSliderState extends State<BannerSlider> {
  late PageController _controller;
  int _currentIndex = 0;

  final List<String> _bannerUrls = [
    'https://th.bing.com/th/id/OIP.1xI_lIGE-IsvGuck629ZOAHaDt?w=205&h=104&c=7&bgcl=2e2f37&r=0&o=6&cb=thws4&dpr=1.6&pid=13.1',
    'https://th.bing.com/th/id/OIP.txO47ZXKPeh67u1DksAmKAHaF7?w=128&h=104&c=7&bgcl=862b90&r=0&o=6&cb=thws4&dpr=1.6&pid=13.1',
    'https://th.bing.com/th/id/OIP.7LZD86Q_wDPf7Az2VypyMwHaEo?w=164&h=104&c=7&bgcl=171543&r=0&o=6&cb=thws4&dpr=1.6&pid=13.1',
  ];

  @override
  void initState() {
    super.initState();
    _controller = PageController();
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  void _goToPrevious() {
    if (_currentIndex > 0) {
      _controller.previousPage(duration: const Duration(milliseconds: 300), curve: Curves.easeInOut);
    } else {
      _controller.jumpToPage(_bannerUrls.length - 1); // quay về cuối
    }
  }

  void _goToNext() {
    if (_currentIndex < _bannerUrls.length - 1) {
      _controller.nextPage(duration: const Duration(milliseconds: 300), curve: Curves.easeInOut);
    } else {
      _controller.jumpToPage(0); // quay về đầu
    }
  }

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 180,
      child: Stack(
        children: [
          ClipRRect(

            child: PageView.builder(
              controller: _controller,
              itemCount: _bannerUrls.length,
              onPageChanged: (index) {
                setState(() {
                  _currentIndex = index;
                });
              },
              itemBuilder: (context, index) {
                return Image.network(
                  _bannerUrls[index],
                  width: double.infinity,
                  fit: BoxFit.cover,
                );
              },
            ),
          ),
          Positioned(
            left: 8,
            top: 70,
            child: IconButton(
              icon: const Icon(Icons.arrow_back_ios, color: Colors.white),
              onPressed: _goToPrevious,
            ),
          ),
          Positioned(
            right: 8,
            top: 70,
            child: IconButton(
              icon: const Icon(Icons.arrow_forward_ios, color: Colors.white),
              onPressed: _goToNext,
            ),
          ),
        ],
      ),
    );
  }
}
