{{ csrf_field() }}

<div class="form-group">
    <label for="name">Nombre:</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">

</div>

<div class="form-group">
    <label for="email">Correo:</label>
    <input type="text" name="email" class="form-control" value="{{ old('email', $user->email) }}">
</div>

<div class="form-group">
    <label for="password">Contraseña:</label>
    <input type="password" name="password" class="form-control" value="{{ old('password') }}">
</div>

<div class="form-group">
    <label for="bio">Biografía</label>
    <textarea type="text" name="bio" class="form-control">{{ old('bio', $user->profile->bio) }}</textarea>
</div>

<div class="form-group">
    <label for="twitter">Twitter</label>
    <input type="text" name="twitter" class="form-control" value="{{ old('twitter', $user->profile->twitter) }}" placeholder="Url de tu usuario de twitter">
</div>

<div class = "form-group">
    <label for="profession_id">Profesion</label>
    <select name="profession_id" id="profession_id" class="form-control">
        <option value="">Selecciona una opcion</option>
        @foreach($professions as $profession)
            <option value="{{ $profession->id }}"
                    {{ old('profession_id', $user->profile->profession_id) == $profession->id ? 'selected' : '' }}
            >{{ $profession->title }}</option>
        @endforeach
    </select>
</div>

<h5>Habilidades</h5>

@foreach($skills as $skill)
    <div class="form-check form-check-inline">
        <input name="skills[{{ $skill->id }}]" class="form-check-input" type="checkbox" id="skill_{{ $skill->id }}"
               value="{{ $skill->id }}" {{ $errors->any() ? old('skills.' . $skill->id) : ($user->skills->contains($skill) ? 'checked' : '') }}>
        <label class="form-check-label" for="skill_{{ $skill->id }}">{{ $skill->name }}</label>
    </div>
@endforeach

<h5>Rol</h5>

@foreach($roles as $role => $name)
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="role" id="role_{{ $role }}" value="{{ $role }}"
                {{ old('role') == $role ? 'checked' : '' }}>
        <label class="form-check-label" for="role_{{ $role }}">{{ $name }}</label>
    </div>
@endforeach