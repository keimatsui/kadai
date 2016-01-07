data = {{1, 10}, {2, 15}, {3, 18}, {4, 21}, {5, 23}, {6, 23}, {7, 23}};

SetOptions[ListPlot, BaseStyle -> {FontSize -> 14}];

ListPlot[data, Joined -> True, Frame -> True, 
 PlotMarkers -> Automatic, FrameLabel -> {"試行年数", "利用関数の累積数"}, 
 PlotRange -> {{1/2, 13/2}, {0, 25}}]

Export["c:/tmp/code.pdf", %]