samples = dir('data/raw');
frame_period = 5;
bin = '.\data\bin\';
for i = 1 : length(samples)
    sample = samples(i).name;
    if(~strcmp(sample, '.') && ~strcmp(sample, '..'))
        base = sample(1 : length(sample) - 4);
        % convert to wav
        system([bin, 'raw2wav 48000 16 data/raw/', base, '.raw', ' data/wav/', base, '.wav']);
        param.F0frameUpdateInterval = 5.0;
        param.spectralUpdateInterval = 5.0;
        disp(['Extracting features from ', base, '.raw']);
        [x,fs] = audioread(['data/wav/', base, '.wav']);
        delete(['data/wav/', base, '.wav']);
        [f0,ap] = exstraightsource(x, fs, param);
        [sp] = exstraightspec(x, f0, fs, param);
        ap = ap';
        sp = sp';
        sp = sp*32768.0;
        save([base, '.f0'], 'f0', '-ascii');
        unix([bin, 'x2x +af ', base, '.f0 | ', bin, 'sopr -magic 0.0 -LN -MAGIC -1.0E+10 > data/lf0/', base, '.lf0']);
        delete([base, '.f0']);
        save([base, '.sp'], 'sp', '-ascii');
        unix([bin, 'x2x +af ', base, '.sp | ', bin, 'mgcep -a 0.77 -m 59 -l 4096 -e 1.0E-08 -j 0 -f 0.0 -q 3 > data/mgc/', base, '.mgc']);
        delete([base, '.sp']);
        save([base, '.ap'], 'ap', '-ascii');
        unix([bin, 'x2x +af ', base, '.ap | ', bin, 'mgcep -a 0.77 -m 24 -l 4096 -e 1.0E-08 -j 0 -f 0.0 -q 1 > data/bap/', base, '.bap']);
        delete([base, '.ap']);
    end;
end;