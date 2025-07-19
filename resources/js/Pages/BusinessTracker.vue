<template>
  <div class="min-h-screen bg-gray-100">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-bold mb-6">Sledovanie údajov o podnikoch</h1>
            
            <!-- Business Selection and Creation -->
            <div class="mb-8 p-4 bg-gray-50 rounded-lg">
              <h2 class="text-lg font-semibold mb-4">Informácie o podniku</h2>
              
              <div class="flex flex-wrap gap-4 mb-4">
                <div class="w-full md:w-auto flex-grow">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Názov podniku</label>
                  <div class="flex gap-2">
                    <select 
                      v-model="form.business_id" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                      <option :value="null" disabled>Vyberte podnik</option>
                      <option v-for="business in businesses" :key="business.id" :value="business.id">
                        {{ business.name }}
                      </option>
                    </select>
                    <button 
                      @click="showNewBusinessForm = !showNewBusinessForm" 
                      class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"
                    >
                      {{ showNewBusinessForm ? 'Zrušiť' : 'Nový' }}
                    </button>
                    <button 
                      v-if="form.business_id && !showNewBusinessForm"
                      @click="showEditBusinessForm = !showEditBusinessForm" 
                      class="px-3 py-2 bg-blue-200 text-gray-700 rounded-md hover:bg-blue-300"
                    >
                      {{ showEditBusinessForm ? 'Zrušiť' : 'Upraviť' }}
                    </button>
                  </div>
                </div>
                
                <div v-if="showNewBusinessForm" class="w-full md:w-auto flex-grow">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Názov nového podniku</label>
                  <div class="flex gap-2">
                    <input 
                      v-model="newBusinessForm.name" 
                      type="text" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Zadajte názov podniku"
                    />
                  </div>
                </div>

                <div v-if="showNewBusinessForm" class="w-full grid grid-cols-1 gap-4 mt-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Počet sedení</label>
                    <input 
                      v-model.number="newBusinessForm.seating_count" 
                      type="number" 
                      min="0"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Zadajte počet sedení"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Počet druhov zmrzliny</label>
                    <input 
                      v-model.number="newBusinessForm.ice_cream_flavors_count" 
                      type="number" 
                      min="0"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Zadajte počet druhov zmrzliny"
                    />
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cena zmrzliny (EUR)</label>
                    <input 
                      v-model.number="newBusinessForm.ice_cream_price" 
                      type="number" 
                      min="0"
                      step="0.01"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Zadajte cenu zmrzliny"
                    />
                  </div>

                  <div class="flex items-center">
                    <label class="inline-flex items-center">
                      <input 
                        type="checkbox" 
                        v-model="newBusinessForm.has_additional_products" 
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      >
                      <span class="ml-2">Má sprievodný tovar</span>
                    </label>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Otváracie hodiny</label>
                    <div class="grid grid-cols-1 gap-4">
                      <div v-for="(day, index) in [
                        { label: 'Pondelok', value: 'monday' },
                        { label: 'Utorok', value: 'tuesday' },
                        { label: 'Streda', value: 'wednesday' },
                        { label: 'Štvrtok', value: 'thursday' },
                        { label: 'Piatok', value: 'friday' },
                        { label: 'Sobota', value: 'saturday' },
                        { label: 'Nedeľa', value: 'sunday' }
                      ]" :key="day.value" class="flex items-center gap-2">
                        <span class="w-24 text-sm text-gray-600">{{ day.label }}</span>
                        <input 
                          v-model="newBusinessForm.business_hours[index].open" 
                          type="time" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <span class="text-gray-500">-</span>
                        <input 
                          v-model="newBusinessForm.business_hours[index].close" 
                          type="time" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                      </div>
                    </div>
                  </div>

                  <div class="w-full mt-4">
                    <button @click="copyBusinessHoursToAllDays" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" > Prekopírovať čas z pondelka na všetky dni </button>
                  </div>
                </div>

                <div v-if="showNewBusinessForm" class="w-full mt-4">
                  <button 
                    @click="createBusiness" 
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50"
                    :disabled="!newBusinessForm.name"
                  >
                    Pridať podnik
                  </button>
                </div>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Current date display -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Dnešný dátum</label>
                  <div class="mt-1 p-2 bg-gray-100 rounded-md text-sm">
                    {{ formatDate(currentDate) }}
                  </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Počasie</label>
                    <select 
                      v-model="form.weather" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                      <option :value="null" disabled>Vyberte počasie</option>
                      <option v-for="weather in weatherOptions" :key="weather" :value="weather">
                        {{ translateWeather(weather) }}
                      </option>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teplota (°C)</label>
                    <input 
                      v-model.number="form.temperature" 
                      type="number" 
                      step="0.1"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Zadajte teplotu"
                    />
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Edit Business Form -->
            <div v-if="showEditBusinessForm" class="mb-8 p-4 bg-yellow-50 rounded-lg">
              <h2 class="text-lg font-semibold mb-4">Upraviť podnik</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Názov podniku</label>
                  <input v-model="editBusinessForm.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Počet sedení</label>
                  <input v-model.number="editBusinessForm.seating_count" type="number" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Počet druhov zmrzliny</label>
                  <input v-model.number="editBusinessForm.ice_cream_flavors_count" type="number" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Cena zmrzliny (EUR)</label>
                  <input v-model.number="editBusinessForm.ice_cream_price" type="number" min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <div class="flex items-center">
                  <label class="inline-flex items-center">
                    <input type="checkbox" v-model="editBusinessForm.has_additional_products" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <span class="ml-2">Má sprievodný tovar</span>
                  </label>
                </div>
              </div>
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Otváracie hodiny</label>
                <div class="grid grid-cols-1 gap-4">
                  <div v-for="(h, idx) in editBusinessForm.business_hours" :key="h.day" class="flex items-center gap-2">
                    <span class="w-24 text-sm text-gray-600 capitalize">{{ h.day }}</span>
                    <input v-model="editBusinessForm.business_hours[idx].open" type="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <span class="text-gray-500">-</span>
                    <input v-model="editBusinessForm.business_hours[idx].close" type="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                  </div>
                </div>
              </div>
              <div class="flex justify-end">
                <button @click="updateBusiness" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50" :disabled="!editBusinessForm.name">Uložiť zmeny</button>
              </div>
            </div>
            
            <!-- Record Entry Form -->
            <div class="mb-8 p-4 bg-gray-50 rounded-lg">
              <h2 class="text-lg font-semibold mb-4">Pridať nový záznam</h2>
              
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Veľkosť skupiny</label>
                  <input 
                    v-model.number="form.group_size" 
                    type="number" 
                    min="1"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Voliteľné"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Nákupy zmrzliny</label>
                  <input 
                    v-model.number="form.ice_cream_purchases" 
                    type="number" 
                    min="0"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Voliteľné"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Spôsob príchodu</label>
                  <select 
                    v-model="form.arrived_by_car" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  >
                    <option :value="null">Neznáme/Nešpecifikované</option>
                    <option :value="true">Autom</option>
                    <option :value="false">Pešo</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Sadli si?</label>
                  <select 
                    v-model="form.seating_status" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  >
                    <option :value="null">Neznáme/Nešpecifikované</option>
                    <option value="sat">Sadli si</option>
                    <option value="not_sat">Nesadli si</option>
                    <option value="no_space">Nebolo miesto</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Kto prišiel?</label>
                  <select 
                    v-model="form.visitor_type" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  >
                    <option :value="null">Neznáme/Nešpecifikované</option>
                    <option value="adults">Dospelí</option>
                    <option value="man_woman">Muž + Žena</option>
                    <option value="family">Rodina</option>
                    <option value="parent_child">Rodič + dieťa</option>
                    <option value="children_only">Deti</option>
                    <option value="teenagers">Teenageri</option>
                    <option value="seniors">Dôchodcovia</option>
                    <option value="senior_children">Dôchodca + deti</option>
                  </select>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Kúpil si aj</label>
                  <div class="flex flex-col gap-2 mt-1">
                    <label class="inline-flex items-center">
                      <input type="checkbox" v-model="form.additional_purchases" value="coffee" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                      <span class="ml-2">Káva</span>
                    </label>
                    <label class="inline-flex items-center">
                      <input type="checkbox" v-model="form.additional_purchases" value="water" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                      <span class="ml-2">Voda</span>
                    </label>
                    <label class="inline-flex items-center">
                      <input type="checkbox" v-model="form.additional_purchases" value="sweets" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                      <span class="ml-2">Sladkosti</span>
                    </label>
                  </div>
                </div>
              </div>
              
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Poznámky</label>
                <textarea 
                  v-model="form.notes" 
                  rows="2"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  placeholder="Voliteľné poznámky k tomuto záznamu"
                ></textarea>
              </div>
              
              <div class="flex justify-end">
                <button 
                  @click="submitRecord" 
                  class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
                  :disabled="!isFormValid"
                >
                  Pridať záznam
                </button>
              </div>
            </div>
            
            <!-- Records Table -->
            <div>
              <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Záznamy podnikov</h2>
                <span class="text-sm text-gray-600">Celkom {{ totalRecords }} záznamov</span>
              </div>
              
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Podnik</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dátum</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Počasie</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teplota</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Veľkosť skupiny</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zmrzliny</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spôsob príchodu</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sadli si</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kto prišiel</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kúpil si aj</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poznámky</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Časová pečiatka</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akcie</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="record in paginatedRecords" :key="record.id" class="hover:bg-gray-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <span>{{ record.business.name }}</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span>{{ formatDate(record.day) }}</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <input 
                          v-if="record.isEditing" 
                          v-model="record.weather" 
                          type="text" 
                          class="w-full border rounded-md px-2 py-1"
                        />
                        <span v-else>{{ translateWeather(record.weather) }}</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <input 
                          v-if="record.isEditing" 
                          v-model.number="record.temperature" 
                          type="number" 
                          step="0.1"
                          class="w-full border rounded-md px-2 py-1"
                        />
                        <span v-else>{{ record.temperature ? `${record.temperature}°C` : '—' }}</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <input 
                          v-if="record.isEditing" 
                          v-model.number="record.group_size" 
                          type="number" 
                          min="1"
                          class="w-full border rounded-md px-2 py-1"
                        />
                        <span v-else>{{ record.group_size || '—' }}</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <input 
                          v-if="record.isEditing" 
                          v-model.number="record.ice_cream_purchases" 
                          type="number" 
                          min="0"
                          class="w-full border rounded-md px-2 py-1"
                        />
                        <span v-else>{{ record.ice_cream_purchases || '—' }}</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <select 
                          v-if="record.isEditing" 
                          v-model="record.arrived_by_car" 
                          class="w-full border rounded-md px-2 py-1"
                        >
                          <option :value="null">Neznáme/Nešpecifikované</option>
                          <option :value="true">Autom</option>
                          <option :value="false">Pešo</option>
                        </select>
                        <span v-else>
                          <span v-if="record.arrived_by_car === true">Autom</span>
                          <span v-else-if="record.arrived_by_car === false">Pešo</span>
                          <span v-else>—</span>
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <select 
                          v-if="record.isEditing" 
                          v-model="record.seating_status" 
                          class="w-full border rounded-md px-2 py-1"
                        >
                          <option :value="null">Neznáme/Nešpecifikované</option>
                          <option value="sat">Sadli si</option>
                          <option value="not_sat">Nesadli si</option>
                          <option value="no_space">Nebolo miesto</option>
                        </select>
                        <span v-else>
                          <span v-if="record.seating_status === 'sat'">Sadli si</span>
                          <span v-else-if="record.seating_status === 'not_sat'">Nesadli si</span>
                          <span v-else-if="record.seating_status === 'no_space'">Nebolo miesto</span>
                          <span v-else>—</span>
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <select 
                          v-if="record.isEditing" 
                          v-model="record.visitor_type" 
                          class="w-full border rounded-md px-2 py-1"
                        >
                          <option :value="null">Neznáme/Nešpecifikované</option>
                          <option value="adults">Dospelí</option>
                          <option value="man_woman">Muž + Žena</option>
                          <option value="family">Rodina</option>
                          <option value="parent_child">Rodič + dieťa</option>
                          <option value="children_only">Deti</option>
                          <option value="teenagers">Teenageri</option>
                          <option value="seniors">Dôchodcovia</option>
                          <option value="senior_children">Dôchodca + deti</option>
                        </select>
                        <span v-else>
                          <span v-if="record.visitor_type === 'family'">Rodina</span>
                          <span v-else-if="record.visitor_type === 'adults'">Dospelí</span>
                          <span v-else-if="record.visitor_type === 'man_woman'">Muž + Žena</span>
                          <span v-else-if="record.visitor_type === 'parent_child'">Rodič + dieťa</span>
                          <span v-else-if="record.visitor_type === 'children_only'">Deti</span>
                          <span v-else-if="record.visitor_type === 'teenagers'">Teenageri</span>
                          <span v-else-if="record.visitor_type === 'seniors'">Dôchodcovia</span>
                          <span v-else-if="record.visitor_type === 'senior_children'">Dôchodca + deti</span>
                          <span v-else>—</span>
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div v-if="record.isEditing" class="flex flex-col gap-2">
                          <label class="inline-flex items-center">
                            <input type="checkbox" v-model="record.additional_purchases" value="coffee" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2">Káva</span>
                          </label>
                          <label class="inline-flex items-center">
                            <input type="checkbox" v-model="record.additional_purchases" value="water" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2">Voda</span>
                          </label>
                          <label class="inline-flex items-center">
                            <input type="checkbox" v-model="record.additional_purchases" value="sweets" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2">Sladkosti</span>
                          </label>
                        </div>
                        <span v-else>
                          <span v-if="record.additional_purchases && record.additional_purchases.length">
                            {{ record.additional_purchases.map(purchase => {
                              const translations = {
                                'coffee': 'Káva',
                                'water': 'Voda',
                                'sweets': 'Sladkosti'
                              };
                              return translations[purchase] || purchase;
                            }).join(', ') }}
                          </span>
                          <span v-else>—</span>
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <textarea 
                          v-if="record.isEditing" 
                          v-model="record.notes" 
                          rows="2" 
                          class="w-full border rounded-md px-2 py-1"
                        ></textarea>
                        <span v-else>{{ record.notes || '—' }}</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ formatDateTime(record.created_at) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <button 
                          v-if="!record.isEditing" 
                          @click="record.isEditing = true" 
                          class="px-2 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                        >
                          Upraviť
                        </button>
                        <button 
                          v-else 
                          @click="saveRecord(record)" 
                          class="px-2 py-1 bg-green-500 text-white rounded-md hover:bg-green-600"
                        >
                          Uložiť
                        </button>
                      </td>
                    </tr>
                    <tr v-if="totalRecords === 0">
                      <td colspan="13" class="px-6 py-4 text-center text-sm text-gray-500">
                        Nenašli sa žiadne záznamy. Pridajte svoj prvý záznam vyššie.
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <!-- Pagination Controls -->
              <div v-if="totalRecords > 0" class="mt-4 flex items-center justify-between flex-col md:flex-row gap-y-3">
                <div class="flex items-center space-x-2">
                  <span class="text-sm text-gray-700">Zobrazených</span>
                  <select 
                    v-model="itemsPerPage" 
                    @change="changeItemsPerPage(itemsPerPage)"
                    class="border-gray-300 rounded-md text-sm"
                  >
                    <option :value="5">5</option>
                    <option :value="10">10</option>
                    <option :value="25">25</option>
                    <option :value="50">50</option>
                    <option :value="100">100</option>
                  </select>
                  <span class="text-sm text-gray-700">záznamov na stránku</span>
                </div>
                
                <div class="flex items-center space-x-2">
                  <span class="text-sm text-gray-700">{{ paginationInfo }}</span>
                </div>
                
                <div class="flex items-center space-x-1">
                  <button 
                    @click="goToPage(1)" 
                    :disabled="currentPage === 1"
                    class="px-2 py-1 text-sm border rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <<
                  </button>
                  
                  <button 
                    @click="prevPage" 
                    :disabled="currentPage === 1"
                    class="px-2 py-1 text-sm border rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                   <
                  </button>
                  
                  <div class="flex space-x-1">
                    <button 
                      v-for="page in getVisiblePages" 
                      :key="page"
                      @click="goToPage(page)"
                      :class="[
                        'px-3 py-1 text-sm border rounded-md',
                        page === currentPage 
                          ? 'bg-blue-500 text-white border-blue-500' 
                          : 'hover:bg-gray-50'
                      ]"
                    >
                      {{ page }}
                    </button>
                  </div>
                  
                  <button 
                    @click="nextPage" 
                    :disabled="currentPage === totalPages"
                    class="px-2 py-1 text-sm border rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    >
                  </button>
                  
                  <button 
                    @click="goToPage(totalPages)" 
                    :disabled="currentPage === totalPages"
                    class="px-2 py-1 text-sm border rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                   >>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { router } from '@inertiajs/vue3';

// Props from the controller
const props = defineProps({
  businesses: Array,
  weatherOptions: Array,
  records: Array
});

// State
const showNewBusinessForm = ref(false);
const showEditBusinessForm = ref(false);
const currentDate = ref(new Date());

// Pagination state
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Update current date every minute
const updateCurrentDate = () => {
  currentDate.value = new Date();
};

// Set up interval to update current date
onMounted(() => {
  // Update immediately
  updateCurrentDate();
  
  // Then update every minute
  const interval = setInterval(updateCurrentDate, 60000);
  
  // Clean up interval on component unmount
  onUnmounted(() => {
    clearInterval(interval);
  });
});

// Form state
const form = ref({
  business_id: null,
  weather: null,
  temperature: null,
  group_size: null,
  ice_cream_purchases: null,
  arrived_by_car: null,
  seating_status: null,
  visitor_type: null,
  additional_purchases: [],
  notes: ''
});

const newBusinessForm = ref({
  name: '',
  seating_count: null,
  ice_cream_flavors_count: null,
  has_additional_products: false,
  ice_cream_price: null,
  business_hours: [
    { day: 'monday', open: '09:00', close: '17:00' },
    { day: 'tuesday', open: '09:00', close: '17:00' },
    { day: 'wednesday', open: '09:00', close: '17:00' },
    { day: 'thursday', open: '09:00', close: '17:00' },
    { day: 'friday', open: '09:00', close: '17:00' },
    { day: 'saturday', open: '09:00', close: '17:00' },
    { day: 'sunday', open: '09:00', close: '17:00' }
  ]
});

// New state for editing
const editBusinessForm = ref({});

// Computed properties
const isFormValid = computed(() => {
  return form.value.business_id && form.value.weather;
});

// Pagination computed properties
const totalRecords = computed(() => props.records.length);
const totalPages = computed(() => Math.ceil(totalRecords.value / itemsPerPage.value));
const paginatedRecords = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return props.records.slice(start, end);
});

const paginationInfo = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value + 1;
  const end = Math.min(currentPage.value * itemsPerPage.value, totalRecords.value);
  return `${start}-${end} z ${totalRecords.value}`;
});

const getVisiblePages = computed(() => {
  const pages = [];
  const maxVisiblePages = 5;
  let startPage = Math.max(1, currentPage.value - Math.floor(maxVisiblePages / 2));
  let endPage = Math.min(totalPages.value, startPage + maxVisiblePages - 1);
  
  // Adjust start page if we're near the end
  if (endPage - startPage + 1 < maxVisiblePages) {
    startPage = Math.max(1, endPage - maxVisiblePages + 1);
  }
  
  for (let i = startPage; i <= endPage; i++) {
    pages.push(i);
  }
  
  return pages;
});

// Watchers
watch(
  () => form.value.business_id,
  (newId) => {
    showEditBusinessForm.value = false;
    if (newId) {
      const biz = props.businesses.find(b => b.id === newId);
      // deep clone to avoid mutating props
      editBusinessForm.value = biz ? JSON.parse(JSON.stringify(biz)) : {};
    } else {
      editBusinessForm.value = {};
    }
  }
);

// Reset to first page when records change
watch(
  () => props.records.length,
  () => {
    if (currentPage.value > totalPages.value) {
      currentPage.value = 1;
    }
  }
);

// Methods
const submitRecord = () => {
  // Create a copy of the form data and add the current date
  const formData = {
    ...form.value,
    day: formatDateForServer(currentDate.value)
  };
  
  router.post('/records', formData, {
    preserveScroll: true,
    onSuccess: () => {
      // Reset optional fields
      form.value.group_size = null;
      form.value.ice_cream_purchases = null;
      form.value.arrived_by_car = null;
      form.value.seating_status = null;
      form.value.visitor_type = null;
      form.value.additional_purchases = [];
      form.value.notes = '';
    }
  });
};

const createBusiness = () => {
  router.post('/businesses', newBusinessForm.value, {
    preserveScroll: true,
    onSuccess: () => {
      showNewBusinessForm.value = false;
      newBusinessForm.value.name = '';
    }
  });
};

const updateBusiness = () => {
  router.put(`/businesses/${form.value.business_id}`, editBusinessForm.value, {
    preserveScroll: true,
    onSuccess: () => {
      showEditBusinessForm.value = false;
    }
  });
};

const saveRecord = (record) => {
  const csrfToken = document.querySelector('meta[name="csrf-token"]');
  if (!csrfToken) {
    console.error('CSRF token not found');
    return;
  }

  fetch(`/records/${record.id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken.getAttribute('content')
    },
    body: JSON.stringify(record)
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Failed to update record');
    }
    return response.json();
  })
  .then(data => {
    Object.assign(record, data.updatedRecord); // Update local data with server response
    record.isEditing = false; // Ensure edit mode is disabled
  })
  .catch(error => {
    console.error('Error updating record:', error);
  });
};

const formatDate = (dateString) => {
  const date = dateString instanceof Date ? dateString : new Date(dateString);
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return date.toLocaleDateString('sk-SK', options);
};

const formatDateTime = (dateTimeString) => {
  const options = { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  };
  return new Date(dateTimeString).toLocaleString('sk-SK', options);
};

// Format date for server (YYYY-MM-DD)
const formatDateForServer = (date) => {
  const d = date instanceof Date ? date : new Date(date);
  return d.toISOString().split('T')[0];
};

// Weather translation function
const translateWeather = (weather) => {
  const translations = {
    'Sunny': 'Slnečno',
    'Cloudy': 'Oblačno',
    'Rainy': 'Daždivo',
    'Windy': 'Veterno'
  };
  
  return translations[weather] || weather;
};

// Capitalize first letter of a string
const capitalize = (str) => {
  if (!str) return '';
  return str.charAt(0).toUpperCase() + str.slice(1);
};

// Copy business hours from the first day to all other days
const copyBusinessHoursToAllDays = () => {
  const firstDayHours = newBusinessForm.value.business_hours[0];
  newBusinessForm.value.business_hours.forEach((dayHours, index) => {
    if (index > 0) {
      dayHours.open = firstDayHours.open;
      dayHours.close = firstDayHours.close;
    }
  });
};

// Pagination methods
const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const changeItemsPerPage = (newItemsPerPage) => {
  itemsPerPage.value = newItemsPerPage;
  currentPage.value = 1; // Reset to first page when changing items per page
};

// Load form data from localStorage on component mount
onMounted(() => {
  const savedForm = localStorage.getItem('businessTrackerForm');
  if (savedForm) {
    Object.assign(form.value, JSON.parse(savedForm));
  }
});

// Save form data to localStorage on change
watch(form, (newForm) => {
  localStorage.setItem('businessTrackerForm', JSON.stringify(newForm));
}, { deep: true });
</script>