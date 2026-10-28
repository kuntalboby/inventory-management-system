@section('title', $details['subject'])
<x-emaillayout>
    
    <main>
        <div
          style="
            margin: 0;
            margin-top: 70px;
            padding: 92px 30px 115px;
            background: #ffffff;
            border-radius: 30px;
            text-align: center;
          "
        >
          <div style="width: 100%; max-width: 489px; margin: 0 auto;">
            <h1
              style="
                margin: 0;
                font-size: 24px;
                font-weight: 500;
                color: #1f1f1f;
              "
            >
             {{$details['mail_title']}}
            </h1>
            <p
              style="
                margin: 0;
                margin-top: 17px;
                font-size: 16px;
                font-weight: 500;
              "
            >
            Hello {{$details['username']}} ,
            </p>
            <p
              style="
                margin: 0;
                margin-top: 17px;
                font-weight: 500;
                letter-spacing: 0.56px;
                font-size:18px
              "
            >
            {{ $details['message']}} 
            </p>
            <p
              style="
                margin: 0;
                margin-top: 60px;
                font-weight: 400;
                color: #14076c;
              "
            >
             <a href="{{ $details['updatelink'] }}">{{ $details['updatelink'] }}</a>
            </p>
          </div>
        </div>

        
      </main>
</x-emaillayout>