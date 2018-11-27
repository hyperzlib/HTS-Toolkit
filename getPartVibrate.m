function [ dep, inv ] = getPartVibrate(f0)
%取得单段的颤音数据
%   此处显示详细说明
    fLen = length(f0);
    t = diff(sign(diff(f0)));
    peaks = find(t < 0);  % 峰值
    valleys = find(t > 0);% 谷值
    dep = zeros(1, fLen);
    inv = zeros(1, fLen);
    % 颤音深度 = (峰值 - 谷值) / 2
    % 颤音程   = | 峰值下标 - 谷值下标 |
    % 颤音起始 = 峰值下标 - 颤音程 / 2
    vLen = min(length(peaks), length(valleys));
    endl = 1;
    depth = 0;
    interval= 0;
    for i = 2 : vLen
        depth = abs(f0(peaks(i)) - f0(valleys(i)));
        interval = abs(valleys(i) - peaks(i));
        start = round(max(1, peaks(i - 1) - interval / 2));
        endl = round(min(fLen, peaks(i) - interval / 2));
        for j = start : endl
            dep(j) = depth;
            inv(j) = interval;
        end;
    end;
    for i = endl : fLen
        dep(i) = depth;
        inv(i) = interval;
    end;
end

