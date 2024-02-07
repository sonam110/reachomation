 <select class="form-select form-select-sm shadow-none accountEmails" id="from" name="from">
      <option value="">Select</option>
      @foreach(emailCollections() as $email)
      <option value="{{ $email->email}}" selected>{{ $email->email}}</option>
      @endforeach
   </select>